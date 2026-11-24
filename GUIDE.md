# Signature POS: Command Reference

Every command used to set up, configure, run and test the project, explained part by part. Run them in the Ubuntu (WSL) terminal unless a section says PowerShell.

## How to read a command

- The first word is the program, for example `git` or `docker`. The words after it say what to do and on what.
- A **short flag** is one dash and one letter, such as `-d`. Letters can be joined: `-rf` means `-r` plus `-f`.
- A **long flag** is two dashes and a word, such as `--ignore-platform-reqs`.
- `|` (pipe) sends the output of the left command into the right command.
- `&&` runs the next command only if the previous one succeeded. `||` runs the next one only if the previous one failed.
- `>` writes output into a file and replaces its content. `>>` adds to the end of the file.
- `$( ... )` runs the command inside first and pastes its result into the outer command.
- `~` is your home folder, `.` is the current folder, `..` is the parent folder.
- `sudo` runs a command with administrator rights.
- `sail` is a shortcut for `./vendor/bin/sail`, set up by the alias in section 3.

## 1. Install WSL (Windows PowerShell as administrator)

```
wsl --install
```

- `wsl`: the Windows Subsystem for Linux program.
- `--install`: installs WSL and the default Ubuntu. Restart Windows afterwards.

```
wsl --install -d Ubuntu-22.04
```

- `-d`: short for `--distribution`, the Linux version to install.
- `Ubuntu-22.04`: the exact Ubuntu release. Only needed for the Docker Engine route.

```
wsl --shutdown
```

- `--shutdown`: stops every WSL machine. Use it to reset WSL or to apply `.wslconfig` changes.

Docker Desktop uses its normal Windows installer (docker.com/products/docker-desktop). Turn on Settings > Resources > WSL integration > Ubuntu.

## 2. Check that Docker works

```
docker --version
docker compose version
```

- `docker`: the Docker program. `--version` prints the installed version, which proves Docker is reachable from Ubuntu.
- `compose`: Docker's tool for running several containers together. Sail uses it. `version` prints its version.

```
docker run hello-world
```

- `run`: create and start a container.
- `hello-world`: a tiny test image. If it prints Hello from Docker, the install works.

```
docker rm $(docker ps -aq)
docker rmi hello-world
```

- `docker ps` lists containers. `-a` (all) includes stopped ones. `-q` (quiet) prints only their IDs. Joined: `-aq`.
- `$( ... )` pastes those IDs into `docker rm`.
- `rm` removes containers. `rmi` removes an image, the template a container is made from.
- Together they delete the test container and the test image.

## 3. Prepare Ubuntu

```
sudo apt update && sudo apt upgrade -y
```

- `sudo`: administrator rights. `apt`: Ubuntu's package installer.
- `update` refreshes the list of available software. `upgrade` installs newer versions. `-y` answers yes to every prompt.
- `&&`: run the upgrade only if the update worked.

```
sudo apt install -y git rsync unzip
```

- `install` adds programs. `git` tracks code, `rsync` copies folders, `unzip` opens zip files.

```
cd ~ && mkdir -p ~/projects && cd ~/projects
```

- `cd` changes folder. `mkdir` makes a folder. `-p` creates parent folders if needed and does not complain if it already exists.

```
echo "alias sail='sh \$([ -f sail ] && echo sail || echo vendor/bin/sail)'" >> ~/.bashrc && source ~/.bashrc
```

- `echo` prints text. The text defines an alias: typing `sail` runs `sh vendor/bin/sail`.
- `[ -f sail ]` tests whether a file named sail exists (`-f` means regular file). If yes it uses `sail`, otherwise `vendor/bin/sail` (`||` means otherwise).
- `\$`: the backslash stops the shell from running `$( ... )` too early.
- `>> ~/.bashrc` appends the line to your shell settings file. `source ~/.bashrc` reloads it now.

```
git --version
git config --global user.name 'Your Name'
git config --global user.email 'you@example.com'
```

- `config` sets a Git option. `--global` applies it to every project on this computer. `user.name` and `user.email` appear on your commits.

```
ssh-keygen -t ed25519 -C 'you@example.com'
cat ~/.ssh/id_ed25519.pub
ssh -T git@github.com
```

- `ssh-keygen` creates a key pair. `-t ed25519` sets the key type (modern and secure). `-C` adds a comment label, usually your email.
- `cat` prints a file. `~/.ssh/id_ed25519.pub` is the public half, which you paste into GitHub. The private file never leaves your computer.
- `ssh` makes a secure connection. `-T` means do not open a terminal, just test. `git@github.com` is the GitHub login. Success says you have authenticated.

## 4. Get the files and the project

```
find /mnt/c/Users/zedri -maxdepth 3 -iname 'signature-pos-foundation*' 2>/dev/null
```

- `find` searches for files. `/mnt/c/...` is your Windows C: drive as seen from Ubuntu.
- `-maxdepth 3` looks at most three folders deep. `-iname` matches the name ignoring upper and lower case. `*` means any ending.
- `2>/dev/null` throws away error messages: `2` is the error channel and `/dev/null` is the trash.

```
cp '/PATH/FROM/FIND' ~/projects/
tar xf signature-pos-foundation*
ls
ls -l
```

- `cp` copies a file. `tar` unpacks archives. `x` means extract. `f` means the next word is the file name. The older form `xzf` also has `z`, meaning the file is gzip-compressed. `tar xf` detects that by itself.
- `ls` lists files. `-l` gives a long list with sizes and dates.

```
git clone git@github.com:ReajDabi/Signature-POS.git
cd Signature-POS
git checkout -b feature/laravel13-foundation
git rm -rq . && git clean -fdxq
```

- `clone` downloads a repository. `checkout` switches branch. `-b` creates the branch first.
- `git rm` removes tracked files. `-r` means recursive (folders too) and `-q` means quiet. The `.` means everything here.
- `git clean` deletes untracked files. `-f` force, `-d` include folders, `-x` include ignored files, `-q` quiet. After both, the folder is empty but the history is kept.

```
curl -s "https://laravel.build/signature-pos-tmp?with=mysql,redis,mailpit" | bash
```

- `curl` downloads from a web address. `-s` means silent (no progress bar).
- The address builds a new Laravel app named signature-pos-tmp. `with=mysql,redis,mailpit` lists the Sail services to include.
- `| bash` runs the downloaded script.

```
rsync -a --exclude='.git/' signature-pos-tmp/ Signature-POS/
sudo rm -rf signature-pos-tmp
```

- `rsync -a` (archive mode) copies everything and keeps permissions and dates. `--exclude='.git/'` skips the Git folder.
- The trailing `/` means copy the contents of the folder, not the folder itself.
- `rm` deletes. `-r` recursive, `-f` force with no questions. This cannot be undone, so check the name first.

```
bash ~/projects/signature-pos-foundation/scripts/apply-foundation.sh ~/projects/Signature-POS
```

- `bash` runs a script. The script copies the foundation files into the project. The path after it says where to copy them.

## 5. Configure the environment

```
sed -i 's|^APP_NAME=.*|APP_NAME="Signature POS"|' .env
sed -i 's|^DB_DATABASE=.*|DB_DATABASE=signature_pos|' .env
```

- `sed` edits text. `-i` edits the file in place.
- `s|old|new|` means substitute. The `|` is the divider. `^APP_NAME=` matches a line starting with APP\_NAME=. `.*` matches everything after it, so the whole line is replaced.
- `.env` is the file with this computer's settings.

```
echo 'FORWARD_DB_PORT=3307' >> .env
```

- Adds a line to the end of `.env`. It moves the database's Windows port from 3306 to 3307 when another MySQL (for example XAMPP) already uses 3306.

```
grep -E '^(APP_NAME|DB_DATABASE|FORWARD_DB_PORT)' .env
grep -n 'DB_' phpunit.xml
```

- `grep` shows matching lines. `-E` allows the `(a|b|c)` pattern, where `|` means or. `^` means start of line.
- `-n` shows line numbers. The second command shows which database the tests use: a separate MySQL database named testing.

```
cat > bootstrap/app.php <<'EOF'
...file text...
EOF
cat bootstrap/app.php
```

- `cat > file` writes the text into the file and replaces it. `<<'EOF'` starts a block of text that ends at the line `EOF`. The quotes mean nothing inside is changed.
- `cat bootstrap/app.php` without `>` just prints the file so you can check it.

```
cp .env .env.example
sed -i 's|^APP_KEY=.*|APP_KEY=|; /^FORWARD_DB_PORT=/d' .env.example
git status --short | grep -E '^.. \.env$' || echo 'good: .env is not staged'
```

- `cp a b` copies file a to b. This makes the shareable template that teammates copy.
- In `sed`, `;` separates two edits. The first empties APP\_KEY, because every computer makes its own. `/pattern/d` deletes lines matching the pattern (`d` means delete).
- `git status --short` gives a compact list of changed files. `grep -E '^.. \.env$'` looks for a line ending in a file named exactly .env. `\.` is a literal dot and `$` is the end of the line.
- `|| echo ...` prints the good message if grep finds nothing. It confirms the secrets file is not going to GitHub.

Teammates:

```
cp .env.example .env
```

- Creates their own `.env` from the template.

## 6. Install dependencies with Sail

```
./vendor/bin/sail up -d
./vendor/bin/sail ps
./vendor/bin/sail down
```

- `./vendor/bin/sail` is the Sail script inside the project (shortcut: `sail`). `up` starts all services: app, MySQL, Redis and Mailpit. `-d` means detached, running in the background.
- `ps` lists the services and whether they are running. `down` stops and removes them. Your database data stays in its volume.

```
./vendor/bin/sail composer require spatie/laravel-permission
```

- `composer` is PHP's package manager, run inside the container. `require` downloads a package and adds it to composer.json. `spatie/laravel-permission` is the roles and permissions package.

```
./vendor/bin/sail artisan vendor:publish --provider='Spatie\Permission\PermissionServiceProvider'
```

- `artisan` is Laravel's command tool. `vendor:publish` copies a package's config and migration files into your project.
- `--provider=...` publishes only that package's files. Single quotes keep the backslashes. Without a valid `--provider`, Laravel opens an interactive menu. Press Ctrl+C to leave it.

```
ls config/permission.php
ls database/migrations | grep permission
```

- Checks that the files were copied. The pipe filters the migration list to lines containing the word permission.

Teammates only (no PHP on their PC, so Composer runs in a temporary container):

```
docker run --rm -u $(id -u):$(id -g) -v $(pwd):/var/www/html -w /var/www/html laravelsail/php84-composer:latest composer install --ignore-platform-reqs
```

- `--rm` deletes the container afterwards. `-u` runs as this user. `id -u` and `id -g` print your user and group numbers, so new files belong to you.
- `-v host:container` shares a folder. `$(pwd)` is the current folder. `-w` sets the working folder inside the container.
- `laravelsail/php84-composer:latest` is the image (PHP 8.4 with Composer). `composer install` downloads every package listed in composer.lock. `--ignore-platform-reqs` skips PHP version checks inside the temporary container.

```
./vendor/bin/sail artisan key:generate
./vendor/bin/sail npm install
```

- `key:generate` creates the app's encryption key in `.env`. `npm install` downloads the front-end packages (Tailwind, Alpine, Vite).

## 7. Migrate and seed the database

```
./vendor/bin/sail artisan migrate:fresh --seed
```

- `migrate` creates or changes tables using the migration files. `:fresh` first drops every table, then runs all migrations. All data is erased.
- `--seed` then fills the tables with demo data: roles, branches, users, services and inventory.

```
./vendor/bin/sail artisan migrate --seed
```

- Without `:fresh` it only runs migrations that have not run yet. Teammates use this the first time.

## 8. Run the system

```
./vendor/bin/sail up -d
./vendor/bin/sail npm run dev
code .
```

- `npm run dev` starts Vite, which builds the CSS and JavaScript while you work. `run` runs a script from package.json and `dev` is its name.
- `code .` opens the current folder in VS Code.
- Open http://localhost (or the `APP_PORT` you set) for the app and http://localhost:8025 for Mailpit, the fake email inbox.
- Other handy commands: `sail shell` opens a terminal inside the app container, and `sail mysql` opens the MySQL console.

## 9. Test and check style

```
./vendor/bin/sail artisan test
./vendor/bin/sail bin pint
```

- `artisan test` runs all automated tests. Expect every test to pass.
- `bin pint` runs Laravel Pint from the vendor/bin folder and fixes code style. Add `--test` to only report problems, as the GitHub check does.
- `sail test` is a shortcut for `artisan test`.

## 10. Save work with Git

```
git add -A
git commit -m 'feat(foundation): laravel 13 on sail, tenancy, audit log, schema'
git push -u origin feature/laravel13-foundation
git checkout develop && git pull
```

- `add -A` stages every change. `commit -m` saves a snapshot, and `-m` is the message. Messages follow Conventional Commits: type(scope): short description.
- `push` uploads. `-u` remembers `origin` and this branch, so a plain `git push` works next time. `origin` is the GitHub copy.
- `pull` downloads and merges the latest changes.
