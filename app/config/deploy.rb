set :application, "zen-wigo"
set :domain,      "zen.terravision.eu"
set :deploy_to,   "/home/deploy/public_html/zen-wig.terravision.eu"
set :app_path,    "app"
set :user,         "deploy"

set :repository,  "git@github.com:proudlygeek/wigo.git"
set :branch,      "master"
set :scm,         :git

set :model_manager, "doctrine"

role :web,        domain                         # Your HTTP server, Apache/etc
role :app,        domain                         # This may be the same as your `Web` server
role :db,         domain, :primary => true       # This is where Symfony2 migrations will run


default_run_options[:pty] = true
ssh_options[:forward_agent] = true

set :vendors_mode, "install"
set :use_composer, true

set  :keep_releases,  3
set  :use_sudo,   false

set :shared_files,    ["app/config/parameters.ini", "app/config/hosts.yml" ]
set :shared_children, [app_path + "/logs", web_path + "/uploads", "vendor"]

# Be more verbose by uncommenting the following line
logger.level = Logger::MAX_LEVEL