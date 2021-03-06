#!/bin/bash -e
# This script publishes the latest state to WordPress Plugin Subversion Repository
# Note: this script requires Subversion 1.7 or later

SVN_REPO=https://plugins.svn.wordpress.org/redpen/trunk/
SVN_USER=redpen

cd `dirname $0`

GIT_BRANCH=$TRAVIS_BRANCH
[ ! "$GIT_BRANCH" ] && GIT_BRANCH=`git rev-parse --abbrev-ref HEAD`

if [ "$TRAVIS_PULL_REQUEST" == true -o "$GIT_BRANCH" != master ]; then
  echo "Not publishing to svn from branch $GIT_BRANCH"
  exit 1
fi

if [ `git status --porcelain | wc -l` != 0 ]; then
  git status
  echo "Please commit all the changes to git first"
  exit 2
fi

if [ ! -d .svn ]; then
  echo "Registering current directory also as Subversion working tree"
  svn checkout $SVN_REPO svn
  mv svn/.svn .
  rm -fr svn
else
  svn update
fi

if ! svn diff redpen.php | fgrep "+Version: "; then
  echo "Version haven't changed, not publishing to $SVN_REPO"
  exit 0
fi

GIT_REV=`git rev-parse --short HEAD`
UNTRACKED_IN_SVN=`svn status | grep '^\?' | sed 's/^\?       //'`

[ ! -z "$UNTRACKED_IN_SVN" ] && svn add "$UNTRACKED_IN_SVN"

svn commit --username $SVN_USER --password "$SVN_PWD" --no-auth-cache -m "git: $GIT_REV"

echo "Changes published to $SVN_REPO"
