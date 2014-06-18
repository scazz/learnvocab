set :application, "LearnVocab"
set :domain,      "#{application}.net"
set :deploy_to,   "/www/#{domain}"
set :app_path,    "app"

set :repository,  "https://github.com/scazz/learnvocab.git"
set :scm,         :git
# Or: `accurev`, `bzr`, `cvs`, `darcs`, `subversion`, `mercurial`, `perforce`, or `none`

set :model_manager, "doctrine"
# Or: `propel`

role :web,        domain                         # Your HTTP server, Apache/etc
role :app,        domain, :primary => true       # This may be the same as your `Web` server

set  :keep_releases,  3

# Be more verbose by uncommenting the following line
# logger.level = Logger::MAX_LEVEL