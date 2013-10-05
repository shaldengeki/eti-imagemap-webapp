# !/usr/bin/env python

''' 
  image_gps_modules - Provides functions for image_gps_daemon.
  Author - Shal Dengeki <shaldengeki@gmail.com>
'''
import update_daemon
import albatross
import pyparallelcurl
import urllib
import re

class Modules(update_daemon.UpdateModules):
  '''
  Provides modules for llAnimuBot.
  '''
  def __init__(self, daemon):
    super(Modules, self).__init__(daemon)
    self.update_functions.extend([
                                 self.scrape_imagemaps
                                 ])

  def process_imagemap_page(self, text, url, curlHandle, paramArray):
    images = paramArray['images']
    hashes = paramArray['hashes']

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

      images.append({
                    'server': image_server,
                    'hash': image_hash,
                    'filename': image_filename,
                    'type': image_ext
                    })

  def scrape_imagemaps(self):
    '''
    Processes the imagemap scraping queue.
    '''
    if (datetime.datetime.now(tz=pytz.utc) - self.info['last_run_time']) < datetime.timedelta(seconds=5):
      return
    self.info['last_run_time'] = datetime.datetime.now(tz=pytz.utc)
    self.daemon.log.info("Processing imagemap queue.")

    scrape_requests = self.dbs['imagemap'].table('scrape_requests').fields('scrape_requests.user_id', 'scrape_requests.date', 'scrape_requests.password', 'users.name').join('users ON users.id=scrape_requests.user_id').where('password IS NOT NULL').order('date ASC').list()
    for request in scrape_requests:
      # process scrape request.
      eti = albatross.Connection(username=request['name'], password=request['password'], loginSite=albatross.SITE_MOBILE)
      if not eti.loggedIn():
        # incorrect password, or ETI is down.
        self.dbs['imagemap'].table('scrape_requests').set(password=None, progress=100).where(user_id=request['user_id']).update()
        continue

      # get this user's currently-uploaded image hashes.
      user_hashes = self.dbs['imagemap'].table('images').fields('hash').where(user_id=request['user_id']).list()
      user_hashes = {image_hash:1 for image_hash in user_hashes}

      # fetch imagemap's first page to get number of pages.
      imap_first_page_html = eti.page('https://images.endoftheinter.net/imagemap.php').html
      imap_first_page = bs4.BeautifulSoup(imap_first_page_html)
      infobar = imap_first_page.find('div', {'class': 'infobar'})
      last_page_link = infobar.find_all('a')[-1]
      last_page_num = int(albatross.getEnclosedString(last_page_link.attrs['href'], 'page=', ''))

      # process the first imagemap page that we've already gotten.
      images_to_add = []
      parallelcurl_params = {
        'images': images_to_add,
        'hashes': user_hashes
      }
      self.process_imagemap_page(imap_first_page_html, 'https://images.endoftheinter.net/imagemap.php?page=1', None, parallelcurl_params)

      # now loop over all the other pages (if there are any).
      for page_num in range(2, last_page_num+1):
        map_page_params = urllib.urlencode([('page', str(page_num))])
        eti.parallelCurl.startrequest('https://images.endoftheinter.net/imagemap.php?' + map_page_params, self.process_imagemap_page, parallelcurl_params)
      eti.parallelCurl.finishallrequests()

      # add images to the database.
      now_date = datetime.datetime.now(tz=pytz.utc)
      images_to_add = [[image['server'], image['hash'], image['filename'], image['type'], request['user_id'], now_date.strftime('%Y-%m-%d %H:%M:%S'), 0, 0] for image in images_to_add]
      self.dbs['imagemap'].table('images').fields('server', 'hash', 'filename', 'type', 'user_id', 'added_on', 'hits', 'private').values(images_to_add).onDuplicateKeyUpdate('id=id').insert()

      self.daemon.log.info("Inserted " + str(len(images_to_add)) + " images for userID " + str(request['user_id']) + ".")