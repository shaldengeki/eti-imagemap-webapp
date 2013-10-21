# !/usr/bin/env python

''' 
  image_gps_modules - Provides functions for image_gps_daemon.
  Author - Shal Dengeki <shaldengeki@gmail.com>
'''
import bs4
import datetime
import urllib
import re
import pytz

import update_daemon
import albatross
import pyparallelcurl

class Modules(update_daemon.UpdateModules):
  '''
  Provides modules for llAnimuBot.
  '''
  def __init__(self, daemon):
    super(Modules, self).__init__(daemon)
    self.update_functions = [
                              self.scrape_imagemaps
                            ]

  def process_imagemap_page(self, text, url, curlHandle, paramArray):
    images = paramArray['images']
    hashes = paramArray['hashes']
    user_id = paramArray['user_id']
    page_num = paramArray['page_num']
    base_datetime = paramArray['base_datetime']
    private = paramArray['private']

    page_datetime = base_datetime - datetime.timedelta(seconds=page_num)

    # split out the image tags on this imagemap page.
    imap_page = bs4.BeautifulSoup(text)
    for image_block in imap_page.find_all('div', {'class': 'grid_block'}):
      # parse the thumbnail tag for everything but the image extension.
      thumb_tag = image_block.find('img')
      thumb_url_parts = thumb_tag.attrs['src'].split('/')

      domain_parts = thumb_url_parts[2].split('.')
      image_server = int(domain_parts[0][1:])

      image_hash = thumb_url_parts[5]

      if image_hash in hashes:
        # image has already been added. done processing.
        return

      # remove extension from image filename.
      image_filename = '.'.join(thumb_url_parts[6].split('.')[0:-1])

      # parse the image's info link for the image's extension.
      image_info_url = thumb_tag.parent.attrs['href']
      ext_parts = image_info_url.split('/')
      ext_match = re.match(r'.*\.(?P<extension>[a-zA-Z]+)$', ext_parts[-1])
      if not ext_match:
        # No extension given for this image. default to jpg.
        image_ext = u'jpg'
      else:
        image_ext = ext_match.group('extension')

      images.append([image_server, image_hash, image_filename, image_ext, user_id, page_datetime.strftime('%Y-%m-%d %H:%M:%S'), 0, private])

  def scrape_imagemaps(self):
    '''
    Processes the imagemap scraping queue.
    '''
    if (datetime.datetime.now(tz=pytz.utc) - self.info['last_run_time']) < datetime.timedelta(seconds=10):
      return
    self.info['last_run_time'] = datetime.datetime.now(tz=pytz.utc)
    self.daemon.log.info("Processing imagemap queue.")

    scrape_requests = self.dbs['imagemap'].table('scrape_requests').fields('scrape_requests.user_id', 'scrape_requests.date', 'scrape_requests.password', 'users.name').join('users ON users.id=scrape_requests.user_id').where('password IS NOT NULL', progress=0).order('date ASC').list()
    for request in scrape_requests:
      # process scrape request.
      self.daemon.log.info("Processing usermap ID " + str(request['user_id']) + ".")
      self.dbs['imagemap'].table('scrape_requests').set(progress=1).where(user_id=request['user_id']).update()

      eti = albatross.Connection(username=request['name'], password=request['password'], loginSite=albatross.SITE_MOBILE)
      if not eti.loggedIn():
        # incorrect password, or ETI is down.
        # TODO: add reason into update scrape_requests
        self.daemon.log.info("Incorrect password or ETI down for usermap ID " + str(request['user_id']) + ". Skipping.")
        self.dbs['imagemap'].table('scrape_requests').set(password=None, progress=-1).where(user_id=request['user_id']).update()
        continue

      # get this user's currently-uploaded image hashes.
      user_hashes = self.dbs['imagemap'].table('images').fields('hash').where(user_id=request['user_id']).list(valField='hash')
      user_hashes = {image_hash:1 for image_hash in user_hashes}

      base_datetime = datetime.datetime.now(tz=pytz.utc)
      images_to_add = []
      parallelcurl_params = {
        'images': images_to_add,
        'hashes': user_hashes,
        'user_id': request['user_id'],
        'base_datetime': base_datetime,
        'page_num': 1,
        'private': request['private']
      }

      start_page_num = 1
      if request['max_pages'] is None:
        # fetch imagemap's first page to get number of pages.
        imap_first_page_html = eti.page('https://images.endoftheinter.net/imagemap.php').html
        imap_first_page = bs4.BeautifulSoup(imap_first_page_html)
        infobar = imap_first_page.find('div', {'class': 'infobar'})
        last_page_link = infobar.find_all('a')[-1]
        last_page_num = int(albatross.getEnclosedString(last_page_link.attrs['href'], 'page=', ''))

        # process the first imagemap page that we've already gotten.
        start_page_num = 2
        self.process_imagemap_page(imap_first_page_html, 'https://images.endoftheinter.net/imagemap.php?page=1', None, parallelcurl_params)
      else:
        last_page_num = int(request['max_pages'])

      # now loop over all the other pages (if there are any).
      num_pages = last_page_num - 1
      for page_num in range(start_page_num, last_page_num+1):
        map_page_params = urllib.urlencode([('page', str(page_num))])
        parallelcurl_params['page_num'] = page_num
        eti.parallelCurl.startrequest('https://images.endoftheinter.net/imagemap.php?' + map_page_params, self.process_imagemap_page, parallelcurl_params)
      eti.parallelCurl.finishallrequests()

      # add images to the database.
      if images_to_add:
        self.dbs['imagemap'].table('images').fields('server', 'hash', 'filename', 'type', 'user_id', 'created', 'hits', 'private').values(images_to_add).onDuplicateKeyUpdate('id=id').insert()
        self.dbs['imagemap'].table('users').set('image_count=image_count+' + str(len(images_to_add))).where(id=request['user_id']).update()

      # set progress to finished.
      if request['permanent'] > 0:
        # this is a permanent scrape request. insert this back into the queue with the current time.
        current_time = datetime.datetime.now(tz=pytz.utc).strftime('%Y-%m-%d %H:%M:%S')
        self.dbs['imagemap'].table('scrape_requests').set(progress=0, date=current_time).where(user_id=request['user_id']).update()
      else:
        self.dbs['imagemap'].table('scrape_requests').set(password=None, progress=100).where(user_id=request['user_id']).update()

      self.daemon.log.info("Inserted " + str(len(images_to_add)) + " images for userID " + str(request['user_id']) + ".")