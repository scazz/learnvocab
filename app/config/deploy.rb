logger.level = Logger::DEBUG

set :application, "LearnVocab"
set :domain,      "#{application}.net"
set :deploy_to,   "/www/#{domain}"
set :app_path,    "app"

set :repository,  "https://github.com/scazz/learnvocab.git"
set :user,        "scazz"
set :scm,         :git
set   :use_sudo,      false

set :shared_files,      ["app/config/parameters.yml"]
set :shared_children,   [app_path + "/logs", "vendor"]
set :use_composer, true
set :update_vendors, false
set :dump_assetic_assets, true

set :model_manager, "doctrine"



role :web,        domain                         # Your HTTP server, Apache/etc
role :app,        domain, :primary => true       # This may be the same as your `Web` server

set  :keep_releases,  3

# Be more verbose by uncommenting the following line
# logger.level = Logger::MAX_LEVEL