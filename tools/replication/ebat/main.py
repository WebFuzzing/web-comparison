import asyncio
from crawler import Crawler
import shutil
import os
import sys
import argparse

import nest_asyncio

nest_asyncio.apply()

parser = argparse.ArgumentParser()
parser.add_argument('--project', help='Mention project code, for example: trello')
parser.add_argument('--time', help='Mention time budget, for example: 30')
args = parser.parse_args()

if __name__ == '__main__':
    websites = [
        ("http://localhost:8080", 'petclinic'), ("http://localhost:3000/pagekit/index.php/admin/", 'pagekit'),
        ("http://localhost:4000", 'trello'),
        ("http://localhost:1800", 'retroboard'), ("http://localhost:4200", 'splittypie'),
        ("http://localhost:3001", 'dimeshift')
    ]

    if args.project is not None:
        tups = [item for item in websites if item[1] == args.project]
        if len(tups) == 0:
            print('Invalid project code')
            sys.exit(1)
        website = tups[0]
    else:
        website = websites[2]
    time_budget = 30
    if args.time is not None:
        try:
            time_budget = int(args.time)
        except:
            print('provide valid time (in minutes)')
            exit(1)
    session = '/tmp/myChromeSession-' + website[1] + str(hash(website[1]))
    if os.path.isdir(session):
        shutil.rmtree(session)

    crawler: Crawler = Crawler(website[0], website[1], session, time_budget)
    asyncio.get_event_loop().run_until_complete(crawler.start())




