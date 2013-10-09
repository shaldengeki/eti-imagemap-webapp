# !/usr/bin/env python
''' 
  image_gps_daemon - Runs ImageGPS delayed jobs.
  Author - Shal Dengeki <shaldengeki@gmail.com>
  USAGE - python image_gps_daemon.py start|stop|restart
  REQUIRES - update_daemon, yapdi

  python image_gps_daemon.py start starts image_gps_daemon in daemon mode 
  if there is no instance already running.

  python image_gps_daemon.py stop kills any running instance.

  python image_gps_daemon.py restart kills any running instance and
  starts an instance. 
'''

import argparse
import pytz
import datetime
import syslog
import time

import update_daemon
import image_gps_modules
import yapdi

class image_gps(update_daemon.UpdateDaemon):
  def preload(self):
    lastRunTime = self.dbs['imagemap'].table('scrape_requests').fields('date').where('password IS NOT NULL').order('date ASC').limit(1).firstValue()
    if lastRunTime:
      self.info['last_run_time'] = pytz.timezone('Europe/Paris').localize(lastRunTime)
    else:
      # scrape requests queue is empty.
      self.info['last_run_time'] = datetime.datetime.now(tz=pytz.utc)

if __name__ == "__main__":
  parser = argparse.ArgumentParser()
  parser.add_argument("action", choices=["start", "stop", "restart"], 
                      help="start, stop, or restart the daemon")
  parser.add_argument("--config", default='./config.txt',
                      help="path to a config textfile")
  args = parser.parse_args()

  if args.action == "start":
    daemon = yapdi.Daemon(pidfile='/var/run/image_gps_daemon.pid')

    # Check whether an instance is already running
    if daemon.status():
      print("An instance is already running.")
      exit()
    retcode = daemon.daemonize()
    # Execute if daemonization was successful else exit
    if retcode == yapdi.OPERATION_SUCCESSFUL:
      bot = image_gps('image_gps', image_gps_modules, config_file=args.config)
      bot.run()
    else:
      syslog.syslog(syslog.LOG_CRIT, 'Daemonization failed')

  elif args.action == "stop":
    daemon = yapdi.Daemon(pidfile='/var/run/image_gps_daemon.pid')

    # Check whether no instance is running
    if not daemon.status():
      print("No instance running.")
      exit()
    retcode = daemon.kill()
    if retcode == yapdi.OPERATION_FAILED:
      print('Trying to stop running instance failed')

  elif args.action == "restart":
    daemon = yapdi.Daemon(pidfile='/var/run/image_gps_daemon.pid')
    retcode = daemon.restart()
    # Execute if daemonization was successful else exit
    if retcode == yapdi.OPERATION_SUCCESSFUL:
      bot = image_gps('image_gps', image_gps_modules, config_file=args.config)
      bot.run()
    else:
      print('Daemonization failed')