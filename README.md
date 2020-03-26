# What it is
This is a boilerplate that has the following stack:
1. React - frontend
2. Laravel - backend
3. Docker - development and deployment container tool
4. Jenkins - CI/CD
5. Xdebug - debugging
6. Codeception - backend testing
7. Jest & Enzyme - frontend testing
8. AWS - deployment platform
All setup steps are described below so that you can easily start developing.

## Development
1. Go to docker and run `docker-compose up` and if you want to rebuild php container add ` --build`
## Frontend
1. Your starting point is `./src/App.js`. 
Here you have to Context API Providers:
- `ThemeProvider` - allows you to pass theme data to all components
- `UserProvider` - allows you to pass user data to all components. User can be changed via `reducer`
Protected Routes authorize users based on `allowedRoles` prop. Don't worry authorization is done on backend as well.
2. All other elements are in `Components`, `Containers` & `Context` folders.
3. `Signin` & `Signup` containers allow to create and authenticate user

### Frontend Dependencies
React - core of the project, JS library
ReactDOM - allows to render DOM in React

Axios - AJAX request tool

Webpack - JS bundler, collect js, html, css etc. into one or several bundles for lazy loading
Webpack CLI - CLI utility for Webpack

Lodash - TO BE CHECKED

Babel-core - Transforms your ES6 code into ES5
Babel-loader - Webpack helper to transform your JavaScript dependencies (for example, when you import your components into other components) with Babel
Babel-preset-env - Determines which transformations/plugins to use and polyfills (provide modern functionality on older browsers that do not natively support it) based on the browser matrix you want to support
Babel-preset-react - Babel preset for all React plugins, for example turning JSX into functions
Babel-plugin-proposal-class-properties - allows to use state = {} in React

styled-components - used for styling components instead of CSS. Adds auto prefixing of CSS classes & implements CSS-in-JS logic
babel-plugin-styled-components - allows to use css prop for styled components, so you can write like `<div css="padding: 5px"></div>`

CSS-loader - process css
style-loader - injects css to html
sass-loader - process sass
file-loader - process images & icons

html-webpack-plugin - dev dependency inserts <script> to /dist/index.html

jest - testing tools. Jest acts as a **test runner**, **assertion library**, and **mocking library** [![Nice tutorial on Medium](https://medium.com/codeclan/testing-react-with-jest-and-enzyme-20505fec4675)
jest-svg-transformer - allows jest to parse svg
jest-styled-components - allows to test styled components and ignore their auto generated `className`
identity-obj-proxy - allows jest to parse css|styl|less|sass|scss|png|jpg|ttf|woff|woff2
react-test-renderer - for rendering snapshots
enzyme - adds some great additional utility methods for **rendering a component** (or multiple components), **finding elements**, and **interacting with elements**.
enzyme-to-json - provides a better component format for snapshot comparison than Enzyme’s internal component representation. 
snapshotSerializers allows you to minimise code duplication when working with snapshots. 
Without the serializer each time a component is created in a test it must have the enzyme-to-json method .toJson() used individually before it can be passed to Jest’s snapshot matcher, with the serializer you never use it individually.
enzyme-adapter-react-16 - allows Enzyme to work with React
babel-jest - allow jest usage with babel
dotenv - allows to use `.env` to set environment variable. Read here how it should be written in Webpack https://medium.com/@trekinbami/using-environment-variables-in-react-6b0a99d83cf5.
notistack - used for snackbars

# Backend
1. Routes are protected with middleware that is fired when route is used doing checks that are required. 
For example, check that HTTP request contains all required fields.
2. Controller actions are protected by Gates that are registered in `AuthServiceProvider.php`

## Xdebug
1. Find your local IP address with `ipconfig getifaddr en0`
2. Add it to `Dockerfile_dev` `xdebug.remote_host=10.0.1.11`. All the other configurations are already there
3. Remote port should be equal to debug port in IDE (Preferences->Languages & Frameworks->Debug) `xdebug.remote_port`
4. Add this host to `DBGp Proxy` too
5. In the Preferences->PHP select the interpreter from Docker. It will show you all the images, select php.
6. Add the host to `Servers` with ip of the backend. For example, `localhost:8001`
7. Click to Validate and don't forget to use public folder. [Jet Brains comment why it can be unreachable](https://intellij-support.jetbrains.com/hc/en-us/community/posts/360001818099-Validating-debugger-configuration-Specified-URL-is-not-reachable-404-)
8. You can debug though REST API call only direct call in browser, Postman doesn't work

# Testing
[Codeception](https://github.com/codeception/codeception) is used for testing.
1. It has a general configuration file `codeception.yml` & separate file acceptance `acceptance.suite.yml`, 
functional `functional.suite.yml` & unit `unit.suite.yml` tests
2. Acceptance test uses Selenium with Google WebDriver that is run in Docker. It allows to test web application as if
it was a real user, so it is useful for JS SPA. Command to start Selenium `docker run -p 4444:4444 -d selenium/standalone-chrome`
3. To run tests use the following command `docker exec -it docker_php_1 php vendor/bin/codecept run --steps`
4. For testing purpose you should change Axios base url in `.env` to host machine IP as Selenium is in Docker - `10.0.1.11:8001`. 
(!) Currently it is change directly in Axios file.
5. For third party API [donatj\MockWebServer](https://github.com/donatj/mock-webserver) is used and that's why `sockets` extension is install in the container. 
As well as `procps` so that ps command can be used.

Commands:
1. Create test: 
- Unit php vendor/bin/codecept generate:test unit Example
- API `php vendor/bin/codecept generate:cest api CreateUser`

# Jenkins
## Initial setup
(!) Very important to use swap for small AWS instances
`sudo fallocate -l 2G /swapfile && 
sudo chmod 600 /swapfile && 
sudo mkswap /swapfile && 
sudo swapon /swapfile`

https://linuxize.com/post/create-a-linux-swap-file/

1. Install Docker
`apt-get update &&
 apt-get install -y apt-transport-https ca-certificates curl software-properties-common &&
 curl -fsSL https://download.docker.com/linux/ubuntu/gpg | apt-key add - &&
 add-apt-repository "deb [arch=amd64] https://download.docker.com/linux/ubuntu bionic stable" &&
 apt-get update &&
 apt-cache policy docker-ce &&
 apt-get install -y docker-ce`
2. Download docker image `docker pull jenkinsci/blueocean`
3. Put AWS access key to `/home/ubuntu/.aws`
4. Launch container in interactive mode, so you can see admin password and copy it. 
 `-v /var/run/docker.sock:/var/run/docker.sock` is used so you can run Docker inside Docker
 `docker run -p 8080:8080 -p 50000:50000 -v /var/jenkins_home:/var/jenkins_home -v /var/run/docker.sock:/var/run/docker.sock jenkinsci/blueocean`
5. Then run container in detached mode `docker run --name jenkins -d -p 8080:8080 -p 50000:50000 -v /var/jenkins_home:/var/jenkins_home -v /var/run/docker.sock:/var/run/docker.sock -v /home/ubuntu/.aws:/root/.aws jenkinsci/blueocean`.
 Here we put the following volumes:<br />
 - jenkins_home - contains all data about jenkins, so when container is restarted you don't have to set up all over again<br />
 - docker.sock - allows to use docker inside Jenkins container without additional installation
 - .aws - aws access key that is used to authenticate before pushing to ECR
6. Install Python`apk add --no-cache --update python3`
7. Install AWS CLI as non root `pip3 install awscli --upgrade --user`
8. Get AWS Credentials `$(/root/.local/bin/aws ecr get-login --no-include-email --region eu-central-1)`
9. Give rights to `ubuntu` user on remote host for `var/run/docker.sock`
10. Install AWS CLI on remote host
11. Start mysql `docker run -p 3308:3306 --name mysql_vacation -v /db_volume:/var/lib/mysql -e MYSQL_ROOT_PASSWORD=ueXXrisTgqP2I-1TmOYU2myQS1TCeVuVL0xZNOxNbXX= -e MYSQL_DATABASE=database -e MYSQL_USER=user -e MYSQL_PASSWORD=ueOQrisTgqP2I+9TmOYU2myQS1TCeVuVL0xZNOxNb44= -d mysql:5.7`

##How to get AWS CLI key
1. Go to [AWS IAM](https://console.aws.amazon.com/iam/home?#/home)
2. Create a new user and attach rights: 
AmazonEC2FullAccess, SecretsManagerReadWrite, AmazonEC2ContainerRegistryFullAccess, AmazonEC2ContainerRegistryPowerUser
3. Download .csv file
4. Run command `aws configure --profile produser` and enter data from the .csv
5. If you use several AWS profiles follow the [instruction](https://docs.aws.amazon.com/cli/latest/userguide/cli-configure-profiles.html) 


##How to get secret credentials
Description is [here](https://codurance.com/2019/05/30/accessing-and-dumping-jenkins-credentials/)
In sshort:
1. Get the credentials hash with inspector
2. Insert it to `http://52.59.247.1:8080/script` like this `println hudson.util.Secret.decrypt("{AQAAABAAAABANx7Sofv4K/ThU0BIB8oUS0bOtZ0xu9UT6sHdHk9lb18+RYF1kcdMhSJ6uKLBd5UFOWX4KDAkZV7HBD8WGabER+qn9rEDlHqeLwrJO69YsvI=}")` 

## Jenkinsfile
1. Backend build is clear 
2. Frontend contains `--build-arg 'arg=.env.test'` that is passed to the Dockerfile where it is used in `ARG arg ENV env_file=$arg` to replace `.env` files.
3. Test part is more interesting and described in the Jenkinsfile
4. Build backend & frontend. Backend is the same & to front we just push a different `.env`
5. Push image to ECR
a. Get credentials for AWS
b. Apply tag that contains data about the registry
c. Delete image
6. Deploy quite clear
7. Environment variable get data from Jenkins credentials and are inserted into `.env` with `sed` command

# SSL Certificate 
1. Go to [https://my.gogetssl.com/](https://my.gogetssl.com/)
2. Create new certificate
3. Verify via email. The easiest way is to do it via Yandex.
4. Get the SSL certificate from the email and add `.ca` file to the bottom of `.crt`
5. Copy the data to `challengepro_ru.crt`
6. If required generate CSR with key at [https://my.gogetssl.com/](https://my.gogetssl.com/). Cope the CSR and the key to `challengepro_ru.key`

# TODO
1. Remove `db_volume` from the repository and find a way to set up test db in another way
2. Rewrite with state pattern `getText(Request $request): string`
3. Apply queue for sending photos
4. Move to new EC2
5. Change RDS security group so it is not accessible from outside
6. Setup debugger to work during tests
7. Replace env check with mock `getPhotoByDestination`

