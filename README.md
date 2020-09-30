# tank-engine
A simple model-controller-view framework for small scale PHP projects. Especially designed for data driven applications. Inspired on (but not based upon) Ruby on Rails.

The framework was written to serve a number of projects for my study association, Van der Waals (www.vdwaals.nl), such as a information screen and a monopoly game.

Currently, I am busy setting things up for this project. This project will be a sort of getting-started version, that works more or less out of the box. It will have another project, [Tank Engine core](https://github.com/tdoel/tank-engine-core), as submodule. My first goal is to ensure that this submodule is properly documented. If that is completed, I will return here.

## Getting started
To use the tank engine for your own project, go to the directory that you want to be the document root of the project.

Run the following commands:
1. `git init`
2. `git remote add origin https://github.com/tdoel/tank-engine`
3. `git pull origin master`
4. `git submodule init`
5. `git submodule update --remote --recursive`

Now copy /framework/boot/0.config.php to /application.boot/0.config.php. Open the newly created file and change all constants to values that correspond to your project.

Open .htacces. The `te_root` depening on your project's url, this piece should be removed or replaced with something else.
Say your url_root is www.example.com, remove it. But if it is www.example.com/exampleproject (so default route is www.example.com/exampleproject/home/index) than replace it with `exampleproject`.
