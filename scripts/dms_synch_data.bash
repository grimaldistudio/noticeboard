#!/bin/bash
rsync -az --delete /home/fabrizio/Sites/dms/protected/uploads/saved /home/fabrizio/Sites/noticeboard/protected/documents
rsync -az --delete /home/fabrizio/Sites/dms/protected/uploads/spendings /home/fabrizio/Sites/noticeboard/protected/documents
