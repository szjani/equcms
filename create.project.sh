#!/bin/sh
svn add application data docs public scripts tests .zfproject.xml defines.php
svn propset svn:ignore '.project
.settings
.buildpath
error.log
access.log
create.project.sh
svn.externals' .
svn propset svn:ignore '*' data/cache tests/log data/logs
svn propset svn:ignore 'pluginLoaderCache.php' data
svn propset svn:ignore 'application.ini' application/configs
svn propset svn:externals . -F svn.externals
