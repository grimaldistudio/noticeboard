#!/bin/bash
rsync -az --delete /home/fabrizio/Sites/dms/protected/uploads/{saved,spendings} /home/fabrizio/Sites/noticeboard/protected/documents
