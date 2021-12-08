



<!DOCTYPE html>
<html lang="en" class="">
  <head prefix="og: http://ogp.me/ns# fb: http://ogp.me/ns/fb# object: http://ogp.me/ns/object# article: http://ogp.me/ns/article# profile: http://ogp.me/ns/profile#">
    <meta charset='utf-8'>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="Content-Language" content="en">
    
    
    <title>aws-sdk-for-php/sdk_compatibility_test.php at master · amazonwebservices/aws-sdk-for-php · GitHub</title>
    <link rel="search" type="application/opensearchdescription+xml" href="/opensearch.xml" title="GitHub">
    <link rel="fluid-icon" href="https://github.com/fluidicon.png" title="GitHub">
    <link rel="apple-touch-icon" sizes="57x57" href="/apple-touch-icon-114.png">
    <link rel="apple-touch-icon" sizes="114x114" href="/apple-touch-icon-114.png">
    <link rel="apple-touch-icon" sizes="72x72" href="/apple-touch-icon-144.png">
    <link rel="apple-touch-icon" sizes="144x144" href="/apple-touch-icon-144.png">
    <meta property="fb:app_id" content="1401488693436528">

      <meta content="@github" name="twitter:site" /><meta content="summary" name="twitter:card" /><meta content="amazonwebservices/aws-sdk-for-php" name="twitter:title" /><meta content="aws-sdk-for-php - (DEPRECATED) AWS SDK for PHP - Version 1. Version 2 is the latest:" name="twitter:description" /><meta content="https://avatars3.githubusercontent.com/u/224077?v=3&amp;s=400" name="twitter:image:src" />
<meta content="GitHub" property="og:site_name" /><meta content="object" property="og:type" /><meta content="https://avatars3.githubusercontent.com/u/224077?v=3&amp;s=400" property="og:image" /><meta content="amazonwebservices/aws-sdk-for-php" property="og:title" /><meta content="https://github.com/amazonwebservices/aws-sdk-for-php" property="og:url" /><meta content="aws-sdk-for-php - (DEPRECATED) AWS SDK for PHP - Version 1. Version 2 is the latest:" property="og:description" />

      <meta name="browser-stats-url" content="/_stats">
    <link rel="assets" href="https://assets-cdn.github.com/">
    <link rel="conduit-xhr" href="https://ghconduit.com:25035">
    
    <meta name="pjax-timeout" content="1000">
    

    <meta name="msapplication-TileImage" content="/windows-tile.png">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="selected-link" value="repo_source" data-pjax-transient>
      <meta name="google-analytics" content="UA-3769691-2">

    <meta content="collector.githubapp.com" name="octolytics-host" /><meta content="collector-cdn.github.com" name="octolytics-script-host" /><meta content="github" name="octolytics-app-id" /><meta content="68AE95AE:6D6B:B746722:547E719B" name="octolytics-dimension-request_id" />
    
    <meta content="Rails, view, blob#show" name="analytics-event" />

    
    
    <link rel="icon" type="image/x-icon" href="https://assets-cdn.github.com/favicon.ico">


    <meta content="authenticity_token" name="csrf-param" />
<meta content="TyuHg5nxCfJDQkz1/a4nF6vhkfo2Q1RTK3e97Dalpy09JnK65E1fXAEfdai8Km7VI70wdySU7U0AfP6tPxBY+w==" name="csrf-token" />

    <link href="https://assets-cdn.github.com/assets/github-3b230f82a0b851c99d89b2bf05aa48231c73dbb9ac7a3e425b3d9751746b4eb5.css" media="all" rel="stylesheet" type="text/css" />
    <link href="https://assets-cdn.github.com/assets/github2-d4d4e2a75ec2546823cca20202cbb9e3fe55733eba0f250a3d0f42ffe69f38c1.css" media="all" rel="stylesheet" type="text/css" />
    
    


    <meta http-equiv="x-pjax-version" content="58a84b398b369f1fbddad41fdc23c6f3">

      
  <meta name="description" content="aws-sdk-for-php - (DEPRECATED) AWS SDK for PHP - Version 1. Version 2 is the latest:">
  <meta name="go-import" content="github.com/amazonwebservices/aws-sdk-for-php git https://github.com/amazonwebservices/aws-sdk-for-php.git">

  <meta content="224077" name="octolytics-dimension-user_id" /><meta content="amazonwebservices" name="octolytics-dimension-user_login" /><meta content="946996" name="octolytics-dimension-repository_id" /><meta content="amazonwebservices/aws-sdk-for-php" name="octolytics-dimension-repository_nwo" /><meta content="true" name="octolytics-dimension-repository_public" /><meta content="false" name="octolytics-dimension-repository_is_fork" /><meta content="946996" name="octolytics-dimension-repository_network_root_id" /><meta content="amazonwebservices/aws-sdk-for-php" name="octolytics-dimension-repository_network_root_nwo" />
  <link href="https://github.com/amazonwebservices/aws-sdk-for-php/commits/master.atom" rel="alternate" title="Recent Commits to aws-sdk-for-php:master" type="application/atom+xml">

  </head>


  <body class="logged_out  env-production macintosh vis-public page-blob">
    <a href="#start-of-content" tabindex="1" class="accessibility-aid js-skip-to-content">Skip to content</a>
    <div class="wrapper">
      
      
      
      


      
      <div class="header header-logged-out" role="banner">
  <div class="container clearfix">

    <a class="header-logo-wordmark" href="https://github.com/" ga-data-click="(Logged out) Header, go to homepage, icon:logo-wordmark">
      <span class="mega-octicon octicon-logo-github"></span>
    </a>

    <div class="header-actions" role="navigation">
        <a class="button primary" href="/join" data-ga-click="(Logged out) Header, clicked Sign up, text:sign-up">Sign up</a>
      <a class="button" href="/login?return_to=%2Famazonwebservices%2Faws-sdk-for-php%2Fblob%2Fmaster%2F_compatibility_test%2Fsdk_compatibility_test.php" data-ga-click="(Logged out) Header, clicked Sign in, text:sign-in">Sign in</a>
    </div>

    <div class="site-search repo-scope js-site-search" role="search">
      <form accept-charset="UTF-8" action="/amazonwebservices/aws-sdk-for-php/search" class="js-site-search-form" data-global-search-url="/search" data-repo-search-url="/amazonwebservices/aws-sdk-for-php/search" method="get"><div style="margin:0;padding:0;display:inline"><input name="utf8" type="hidden" value="&#x2713;" /></div>
  <input type="text"
    class="js-site-search-field is-clearable"
    data-hotkey="s"
    name="q"
    placeholder="Search"
    data-global-scope-placeholder="Search GitHub"
    data-repo-scope-placeholder="Search"
    tabindex="1"
    autocapitalize="off">
  <div class="scope-badge">This repository</div>
</form>
    </div>

      <ul class="header-nav left" role="navigation">
          <li class="header-nav-item">
            <a class="header-nav-link" href="/explore" data-ga-click="(Logged out) Header, go to explore, text:explore">Explore</a>
          </li>
          <li class="header-nav-item">
            <a class="header-nav-link" href="/features" data-ga-click="(Logged out) Header, go to features, text:features">Features</a>
          </li>
          <li class="header-nav-item">
            <a class="header-nav-link" href="https://enterprise.github.com/" data-ga-click="(Logged out) Header, go to enterprise, text:enterprise">Enterprise</a>
          </li>
          <li class="header-nav-item">
            <a class="header-nav-link" href="/blog" data-ga-click="(Logged out) Header, go to blog, text:blog">Blog</a>
          </li>
      </ul>

  </div>
</div>



      <div id="start-of-content" class="accessibility-aid"></div>
          <div class="site" itemscope itemtype="http://schema.org/WebPage">
    <div id="js-flash-container">
      
    </div>
    <div class="pagehead repohead instapaper_ignore readability-menu">
      <div class="container">
        
<ul class="pagehead-actions">


  <li>
      <a href="/login?return_to=%2Famazonwebservices%2Faws-sdk-for-php"
    class="minibutton with-count star-button tooltipped tooltipped-n"
    aria-label="You must be signed in to star a repository" rel="nofollow">
    <span class="octicon octicon-star"></span>
    Star
  </a>

    <a class="social-count js-social-count" href="/amazonwebservices/aws-sdk-for-php/stargazers">
      747
    </a>

  </li>

    <li>
      <a href="/login?return_to=%2Famazonwebservices%2Faws-sdk-for-php"
        class="minibutton with-count js-toggler-target fork-button tooltipped tooltipped-n"
        aria-label="You must be signed in to fork a repository" rel="nofollow">
        <span class="octicon octicon-repo-forked"></span>
        Fork
      </a>
      <a href="/amazonwebservices/aws-sdk-for-php/network" class="social-count">
        271
      </a>
    </li>
</ul>

        <h1 itemscope itemtype="http://data-vocabulary.org/Breadcrumb" class="entry-title public">
          <span class="mega-octicon octicon-repo"></span>
          <span class="author"><a href="/amazonwebservices" class="url fn" itemprop="url" rel="author"><span itemprop="title">amazonwebservices</span></a></span><!--
       --><span class="path-divider">/</span><!--
       --><strong><a href="/amazonwebservices/aws-sdk-for-php" class="js-current-repository" data-pjax="#js-repo-pjax-container">aws-sdk-for-php</a></strong>

          <span class="page-context-loader">
            <img alt="" height="16" src="https://assets-cdn.github.com/images/spinners/octocat-spinner-32.gif" width="16" />
          </span>

        </h1>
      </div><!-- /.container -->
    </div><!-- /.repohead -->

    <div class="container">
      <div class="repository-with-sidebar repo-container new-discussion-timeline  ">
        <div class="repository-sidebar clearfix">
            
<nav class="sunken-menu repo-nav js-repo-nav js-sidenav-container-pjax js-octicon-loaders"
     role="navigation"
     data-pjax="#js-repo-pjax-container"
     data-issue-count-url="/amazonwebservices/aws-sdk-for-php/issues/counts">
  <ul class="sunken-menu-group">
    <li class="tooltipped tooltipped-w" aria-label="Code">
      <a href="/amazonwebservices/aws-sdk-for-php" aria-label="Code" class="selected js-selected-navigation-item sunken-menu-item" data-hotkey="g c" data-selected-links="repo_source repo_downloads repo_commits repo_releases repo_tags repo_branches /amazonwebservices/aws-sdk-for-php">
        <span class="octicon octicon-code"></span> <span class="full-word">Code</span>
        <img alt="" class="mini-loader" height="16" src="https://assets-cdn.github.com/images/spinners/octocat-spinner-32.gif" width="16" />
</a>    </li>

      <li class="tooltipped tooltipped-w" aria-label="Issues">
        <a href="/amazonwebservices/aws-sdk-for-php/issues" aria-label="Issues" class="js-selected-navigation-item sunken-menu-item" data-hotkey="g i" data-selected-links="repo_issues repo_labels repo_milestones /amazonwebservices/aws-sdk-for-php/issues">
          <span class="octicon octicon-issue-opened"></span> <span class="full-word">Issues</span>
          <span class="js-issue-replace-counter"></span>
          <img alt="" class="mini-loader" height="16" src="https://assets-cdn.github.com/images/spinners/octocat-spinner-32.gif" width="16" />
</a>      </li>

    <li class="tooltipped tooltipped-w" aria-label="Pull Requests">
      <a href="/amazonwebservices/aws-sdk-for-php/pulls" aria-label="Pull Requests" class="js-selected-navigation-item sunken-menu-item" data-hotkey="g p" data-selected-links="repo_pulls /amazonwebservices/aws-sdk-for-php/pulls">
          <span class="octicon octicon-git-pull-request"></span> <span class="full-word">Pull Requests</span>
          <span class="js-pull-replace-counter"></span>
          <img alt="" class="mini-loader" height="16" src="https://assets-cdn.github.com/images/spinners/octocat-spinner-32.gif" width="16" />
</a>    </li>


  </ul>
  <div class="sunken-menu-separator"></div>
  <ul class="sunken-menu-group">

    <li class="tooltipped tooltipped-w" aria-label="Pulse">
      <a href="/amazonwebservices/aws-sdk-for-php/pulse" aria-label="Pulse" class="js-selected-navigation-item sunken-menu-item" data-selected-links="pulse /amazonwebservices/aws-sdk-for-php/pulse">
        <span class="octicon octicon-pulse"></span> <span class="full-word">Pulse</span>
        <img alt="" class="mini-loader" height="16" src="https://assets-cdn.github.com/images/spinners/octocat-spinner-32.gif" width="16" />
</a>    </li>

    <li class="tooltipped tooltipped-w" aria-label="Graphs">
      <a href="/amazonwebservices/aws-sdk-for-php/graphs" aria-label="Graphs" class="js-selected-navigation-item sunken-menu-item" data-selected-links="repo_graphs repo_contributors /amazonwebservices/aws-sdk-for-php/graphs">
        <span class="octicon octicon-graph"></span> <span class="full-word">Graphs</span>
        <img alt="" class="mini-loader" height="16" src="https://assets-cdn.github.com/images/spinners/octocat-spinner-32.gif" width="16" />
</a>    </li>
  </ul>


</nav>

              <div class="only-with-full-nav">
                
  
<div class="clone-url open"
  data-protocol-type="http"
  data-url="/users/set_protocol?protocol_selector=http&amp;protocol_type=clone">
  <h3><span class="text-emphasized">HTTPS</span> clone URL</h3>
  <div class="input-group js-zeroclipboard-container">
    <input type="text" class="input-mini input-monospace js-url-field js-zeroclipboard-target"
           value="https://github.com/amazonwebservices/aws-sdk-for-php.git" readonly="readonly">
    <span class="input-group-button">
      <button aria-label="Copy to clipboard" class="js-zeroclipboard minibutton zeroclipboard-button" data-copied-hint="Copied!" type="button"><span class="octicon octicon-clippy"></span></button>
    </span>
  </div>
</div>

  
<div class="clone-url "
  data-protocol-type="subversion"
  data-url="/users/set_protocol?protocol_selector=subversion&amp;protocol_type=clone">
  <h3><span class="text-emphasized">Subversion</span> checkout URL</h3>
  <div class="input-group js-zeroclipboard-container">
    <input type="text" class="input-mini input-monospace js-url-field js-zeroclipboard-target"
           value="https://github.com/amazonwebservices/aws-sdk-for-php" readonly="readonly">
    <span class="input-group-button">
      <button aria-label="Copy to clipboard" class="js-zeroclipboard minibutton zeroclipboard-button" data-copied-hint="Copied!" type="button"><span class="octicon octicon-clippy"></span></button>
    </span>
  </div>
</div>


<p class="clone-options">You can clone with
      <a href="#" class="js-clone-selector" data-protocol="http">HTTPS</a>
      or <a href="#" class="js-clone-selector" data-protocol="subversion">Subversion</a>.
  <a href="https://help.github.com/articles/which-remote-url-should-i-use" class="help tooltipped tooltipped-n" aria-label="Get help on which URL is right for you.">
    <span class="octicon octicon-question"></span>
  </a>
</p>

  <a href="http://mac.github.com" data-url="github-mac://openRepo/https://github.com/amazonwebservices/aws-sdk-for-php" class="minibutton sidebar-button js-conduit-rewrite-url" title="Save amazonwebservices/aws-sdk-for-php to your computer and use it in GitHub Desktop." aria-label="Save amazonwebservices/aws-sdk-for-php to your computer and use it in GitHub Desktop.">
    <span class="octicon octicon-device-desktop"></span>
    Clone in Desktop
  </a>


                <a href="/amazonwebservices/aws-sdk-for-php/archive/master.zip"
                   class="minibutton sidebar-button"
                   aria-label="Download the contents of amazonwebservices/aws-sdk-for-php as a zip file"
                   title="Download the contents of amazonwebservices/aws-sdk-for-php as a zip file"
                   rel="nofollow">
                  <span class="octicon octicon-cloud-download"></span>
                  Download ZIP
                </a>
              </div>
        </div><!-- /.repository-sidebar -->

        <div id="js-repo-pjax-container" class="repository-content context-loader-container" data-pjax-container>
          

<a href="/amazonwebservices/aws-sdk-for-php/blob/0507424b05bd191f043375b787640bd1eb4f692b/_compatibility_test/sdk_compatibility_test.php" class="hidden js-permalink-shortcut" data-hotkey="y">Permalink</a>

<!-- blob contrib key: blob_contributors:v21:254f7b6a2db3b83ae769f9fd71c2e8fa -->

<div class="file-navigation js-zeroclipboard-container">
  
<div class="select-menu js-menu-container js-select-menu left">
  <span class="minibutton select-menu-button js-menu-target css-truncate" data-hotkey="w"
    data-master-branch="master"
    data-ref="master"
    title="master"
    role="button" aria-label="Switch branches or tags" tabindex="0" aria-haspopup="true">
    <span class="octicon octicon-git-branch"></span>
    <i>branch:</i>
    <span class="js-select-button css-truncate-target">master</span>
  </span>

  <div class="select-menu-modal-holder js-menu-content js-navigation-container" data-pjax aria-hidden="true">

    <div class="select-menu-modal">
      <div class="select-menu-header">
        <span class="select-menu-title">Switch branches/tags</span>
        <span class="octicon octicon-x js-menu-close" role="button" aria-label="Close"></span>
      </div> <!-- /.select-menu-header -->

      <div class="select-menu-filters">
        <div class="select-menu-text-filter">
          <input type="text" aria-label="Filter branches/tags" id="context-commitish-filter-field" class="js-filterable-field js-navigation-enable" placeholder="Filter branches/tags">
        </div>
        <div class="select-menu-tabs">
          <ul>
            <li class="select-menu-tab">
              <a href="#" data-tab-filter="branches" class="js-select-menu-tab">Branches</a>
            </li>
            <li class="select-menu-tab">
              <a href="#" data-tab-filter="tags" class="js-select-menu-tab">Tags</a>
            </li>
          </ul>
        </div><!-- /.select-menu-tabs -->
      </div><!-- /.select-menu-filters -->

      <div class="select-menu-list select-menu-tab-bucket js-select-menu-tab-bucket" data-tab-filter="branches">

        <div data-filterable-for="context-commitish-filter-field" data-filterable-type="substring">


            <div class="select-menu-item js-navigation-item selected">
              <span class="select-menu-item-icon octicon octicon-check"></span>
              <a href="/amazonwebservices/aws-sdk-for-php/blob/master/_compatibility_test/sdk_compatibility_test.php"
                 data-name="master"
                 data-skip-pjax="true"
                 rel="nofollow"
                 class="js-navigation-open select-menu-item-text css-truncate-target"
                 title="master">master</a>
            </div> <!-- /.select-menu-item -->
        </div>

          <div class="select-menu-no-results">Nothing to show</div>
      </div> <!-- /.select-menu-list -->

      <div class="select-menu-list select-menu-tab-bucket js-select-menu-tab-bucket" data-tab-filter="tags">
        <div data-filterable-for="context-commitish-filter-field" data-filterable-type="substring">


            <div class="select-menu-item js-navigation-item ">
              <span class="select-menu-item-icon octicon octicon-check"></span>
              <a href="/amazonwebservices/aws-sdk-for-php/tree/1.6.2/_compatibility_test/sdk_compatibility_test.php"
                 data-name="1.6.2"
                 data-skip-pjax="true"
                 rel="nofollow"
                 class="js-navigation-open select-menu-item-text css-truncate-target"
                 title="1.6.2">1.6.2</a>
            </div> <!-- /.select-menu-item -->
            <div class="select-menu-item js-navigation-item ">
              <span class="select-menu-item-icon octicon octicon-check"></span>
              <a href="/amazonwebservices/aws-sdk-for-php/tree/1.6.1/_compatibility_test/sdk_compatibility_test.php"
                 data-name="1.6.1"
                 data-skip-pjax="true"
                 rel="nofollow"
                 class="js-navigation-open select-menu-item-text css-truncate-target"
                 title="1.6.1">1.6.1</a>
            </div> <!-- /.select-menu-item -->
            <div class="select-menu-item js-navigation-item ">
              <span class="select-menu-item-icon octicon octicon-check"></span>
              <a href="/amazonwebservices/aws-sdk-for-php/tree/1.6.0/_compatibility_test/sdk_compatibility_test.php"
                 data-name="1.6.0"
                 data-skip-pjax="true"
                 rel="nofollow"
                 class="js-navigation-open select-menu-item-text css-truncate-target"
                 title="1.6.0">1.6.0</a>
            </div> <!-- /.select-menu-item -->
            <div class="select-menu-item js-navigation-item ">
              <span class="select-menu-item-icon octicon octicon-check"></span>
              <a href="/amazonwebservices/aws-sdk-for-php/tree/1.5.17.1/_compatibility_test/sdk_compatibility_test.php"
                 data-name="1.5.17.1"
                 data-skip-pjax="true"
                 rel="nofollow"
                 class="js-navigation-open select-menu-item-text css-truncate-target"
                 title="1.5.17.1">1.5.17.1</a>
            </div> <!-- /.select-menu-item -->
            <div class="select-menu-item js-navigation-item ">
              <span class="select-menu-item-icon octicon octicon-check"></span>
              <a href="/amazonwebservices/aws-sdk-for-php/tree/1.5.17/_compatibility_test/sdk_compatibility_test.php"
                 data-name="1.5.17"
                 data-skip-pjax="true"
                 rel="nofollow"
                 class="js-navigation-open select-menu-item-text css-truncate-target"
                 title="1.5.17">1.5.17</a>
            </div> <!-- /.select-menu-item -->
            <div class="select-menu-item js-navigation-item ">
              <span class="select-menu-item-icon octicon octicon-check"></span>
              <a href="/amazonwebservices/aws-sdk-for-php/tree/1.5.16.1/_compatibility_test/sdk_compatibility_test.php"
                 data-name="1.5.16.1"
                 data-skip-pjax="true"
                 rel="nofollow"
                 class="js-navigation-open select-menu-item-text css-truncate-target"
                 title="1.5.16.1">1.5.16.1</a>
            </div> <!-- /.select-menu-item -->
            <div class="select-menu-item js-navigation-item ">
              <span class="select-menu-item-icon octicon octicon-check"></span>
              <a href="/amazonwebservices/aws-sdk-for-php/tree/1.5.16/_compatibility_test/sdk_compatibility_test.php"
                 data-name="1.5.16"
                 data-skip-pjax="true"
                 rel="nofollow"
                 class="js-navigation-open select-menu-item-text css-truncate-target"
                 title="1.5.16">1.5.16</a>
            </div> <!-- /.select-menu-item -->
            <div class="select-menu-item js-navigation-item ">
              <span class="select-menu-item-icon octicon octicon-check"></span>
              <a href="/amazonwebservices/aws-sdk-for-php/tree/1.5.15/_compatibility_test/sdk_compatibility_test.php"
                 data-name="1.5.15"
                 data-skip-pjax="true"
                 rel="nofollow"
                 class="js-navigation-open select-menu-item-text css-truncate-target"
                 title="1.5.15">1.5.15</a>
            </div> <!-- /.select-menu-item -->
            <div class="select-menu-item js-navigation-item ">
              <span class="select-menu-item-icon octicon octicon-check"></span>
              <a href="/amazonwebservices/aws-sdk-for-php/tree/1.5.14/_compatibility_test/sdk_compatibility_test.php"
                 data-name="1.5.14"
                 data-skip-pjax="true"
                 rel="nofollow"
                 class="js-navigation-open select-menu-item-text css-truncate-target"
                 title="1.5.14">1.5.14</a>
            </div> <!-- /.select-menu-item -->
            <div class="select-menu-item js-navigation-item ">
              <span class="select-menu-item-icon octicon octicon-check"></span>
              <a href="/amazonwebservices/aws-sdk-for-php/tree/1.5.13/_compatibility_test/sdk_compatibility_test.php"
                 data-name="1.5.13"
                 data-skip-pjax="true"
                 rel="nofollow"
                 class="js-navigation-open select-menu-item-text css-truncate-target"
                 title="1.5.13">1.5.13</a>
            </div> <!-- /.select-menu-item -->
            <div class="select-menu-item js-navigation-item ">
              <span class="select-menu-item-icon octicon octicon-check"></span>
              <a href="/amazonwebservices/aws-sdk-for-php/tree/1.5.12/_compatibility_test/sdk_compatibility_test.php"
                 data-name="1.5.12"
                 data-skip-pjax="true"
                 rel="nofollow"
                 class="js-navigation-open select-menu-item-text css-truncate-target"
                 title="1.5.12">1.5.12</a>
            </div> <!-- /.select-menu-item -->
            <div class="select-menu-item js-navigation-item ">
              <span class="select-menu-item-icon octicon octicon-check"></span>
              <a href="/amazonwebservices/aws-sdk-for-php/tree/1.5.11/_compatibility_test/sdk_compatibility_test.php"
                 data-name="1.5.11"
                 data-skip-pjax="true"
                 rel="nofollow"
                 class="js-navigation-open select-menu-item-text css-truncate-target"
                 title="1.5.11">1.5.11</a>
            </div> <!-- /.select-menu-item -->
            <div class="select-menu-item js-navigation-item ">
              <span class="select-menu-item-icon octicon octicon-check"></span>
              <a href="/amazonwebservices/aws-sdk-for-php/tree/1.5.10/_compatibility_test/sdk_compatibility_test.php"
                 data-name="1.5.10"
                 data-skip-pjax="true"
                 rel="nofollow"
                 class="js-navigation-open select-menu-item-text css-truncate-target"
                 title="1.5.10">1.5.10</a>
            </div> <!-- /.select-menu-item -->
            <div class="select-menu-item js-navigation-item ">
              <span class="select-menu-item-icon octicon octicon-check"></span>
              <a href="/amazonwebservices/aws-sdk-for-php/tree/1.5.9/_compatibility_test/sdk_compatibility_test.php"
                 data-name="1.5.9"
                 data-skip-pjax="true"
                 rel="nofollow"
                 class="js-navigation-open select-menu-item-text css-truncate-target"
                 title="1.5.9">1.5.9</a>
            </div> <!-- /.select-menu-item -->
            <div class="select-menu-item js-navigation-item ">
              <span class="select-menu-item-icon octicon octicon-check"></span>
              <a href="/amazonwebservices/aws-sdk-for-php/tree/1.5.8.2/_compatibility_test/sdk_compatibility_test.php"
                 data-name="1.5.8.2"
                 data-skip-pjax="true"
                 rel="nofollow"
                 class="js-navigation-open select-menu-item-text css-truncate-target"
                 title="1.5.8.2">1.5.8.2</a>
            </div> <!-- /.select-menu-item -->
            <div class="select-menu-item js-navigation-item ">
              <span class="select-menu-item-icon octicon octicon-check"></span>
              <a href="/amazonwebservices/aws-sdk-for-php/tree/1.5.8.1/_compatibility_test/sdk_compatibility_test.php"
                 data-name="1.5.8.1"
                 data-skip-pjax="true"
                 rel="nofollow"
                 class="js-navigation-open select-menu-item-text css-truncate-target"
                 title="1.5.8.1">1.5.8.1</a>
            </div> <!-- /.select-menu-item -->
            <div class="select-menu-item js-navigation-item ">
              <span class="select-menu-item-icon octicon octicon-check"></span>
              <a href="/amazonwebservices/aws-sdk-for-php/tree/1.5.8/_compatibility_test/sdk_compatibility_test.php"
                 data-name="1.5.8"
                 data-skip-pjax="true"
                 rel="nofollow"
                 class="js-navigation-open select-menu-item-text css-truncate-target"
                 title="1.5.8">1.5.8</a>
            </div> <!-- /.select-menu-item -->
            <div class="select-menu-item js-navigation-item ">
              <span class="select-menu-item-icon octicon octicon-check"></span>
              <a href="/amazonwebservices/aws-sdk-for-php/tree/1.5.7/_compatibility_test/sdk_compatibility_test.php"
                 data-name="1.5.7"
                 data-skip-pjax="true"
                 rel="nofollow"
                 class="js-navigation-open select-menu-item-text css-truncate-target"
                 title="1.5.7">1.5.7</a>
            </div> <!-- /.select-menu-item -->
            <div class="select-menu-item js-navigation-item ">
              <span class="select-menu-item-icon octicon octicon-check"></span>
              <a href="/amazonwebservices/aws-sdk-for-php/tree/1.5.6.2/_compatibility_test/sdk_compatibility_test.php"
                 data-name="1.5.6.2"
                 data-skip-pjax="true"
                 rel="nofollow"
                 class="js-navigation-open select-menu-item-text css-truncate-target"
                 title="1.5.6.2">1.5.6.2</a>
            </div> <!-- /.select-menu-item -->
            <div class="select-menu-item js-navigation-item ">
              <span class="select-menu-item-icon octicon octicon-check"></span>
              <a href="/amazonwebservices/aws-sdk-for-php/tree/1.5.6.1/_compatibility_test/sdk_compatibility_test.php"
                 data-name="1.5.6.1"
                 data-skip-pjax="true"
                 rel="nofollow"
                 class="js-navigation-open select-menu-item-text css-truncate-target"
                 title="1.5.6.1">1.5.6.1</a>
            </div> <!-- /.select-menu-item -->
            <div class="select-menu-item js-navigation-item ">
              <span class="select-menu-item-icon octicon octicon-check"></span>
              <a href="/amazonwebservices/aws-sdk-for-php/tree/1.5.6/_compatibility_test/sdk_compatibility_test.php"
                 data-name="1.5.6"
                 data-skip-pjax="true"
                 rel="nofollow"
                 class="js-navigation-open select-menu-item-text css-truncate-target"
                 title="1.5.6">1.5.6</a>
            </div> <!-- /.select-menu-item -->
            <div class="select-menu-item js-navigation-item ">
              <span class="select-menu-item-icon octicon octicon-check"></span>
              <a href="/amazonwebservices/aws-sdk-for-php/tree/1.5.5/_compatibility_test/sdk_compatibility_test.php"
                 data-name="1.5.5"
                 data-skip-pjax="true"
                 rel="nofollow"
                 class="js-navigation-open select-menu-item-text css-truncate-target"
                 title="1.5.5">1.5.5</a>
            </div> <!-- /.select-menu-item -->
            <div class="select-menu-item js-navigation-item ">
              <span class="select-menu-item-icon octicon octicon-check"></span>
              <a href="/amazonwebservices/aws-sdk-for-php/tree/1.5.4/_compatibility_test/sdk_compatibility_test.php"
                 data-name="1.5.4"
                 data-skip-pjax="true"
                 rel="nofollow"
                 class="js-navigation-open select-menu-item-text css-truncate-target"
                 title="1.5.4">1.5.4</a>
            </div> <!-- /.select-menu-item -->
            <div class="select-menu-item js-navigation-item ">
              <span class="select-menu-item-icon octicon octicon-check"></span>
              <a href="/amazonwebservices/aws-sdk-for-php/tree/1.5.3/_compatibility_test/sdk_compatibility_test.php"
                 data-name="1.5.3"
                 data-skip-pjax="true"
                 rel="nofollow"
                 class="js-navigation-open select-menu-item-text css-truncate-target"
                 title="1.5.3">1.5.3</a>
            </div> <!-- /.select-menu-item -->
            <div class="select-menu-item js-navigation-item ">
              <span class="select-menu-item-icon octicon octicon-check"></span>
              <a href="/amazonwebservices/aws-sdk-for-php/tree/1.5.2/_compatibility_test/sdk_compatibility_test.php"
                 data-name="1.5.2"
                 data-skip-pjax="true"
                 rel="nofollow"
                 class="js-navigation-open select-menu-item-text css-truncate-target"
                 title="1.5.2">1.5.2</a>
            </div> <!-- /.select-menu-item -->
            <div class="select-menu-item js-navigation-item ">
              <span class="select-menu-item-icon octicon octicon-check"></span>
              <a href="/amazonwebservices/aws-sdk-for-php/tree/1.5.1/_compatibility_test/sdk_compatibility_test.php"
                 data-name="1.5.1"
                 data-skip-pjax="true"
                 rel="nofollow"
                 class="js-navigation-open select-menu-item-text css-truncate-target"
                 title="1.5.1">1.5.1</a>
            </div> <!-- /.select-menu-item -->
            <div class="select-menu-item js-navigation-item ">
              <span class="select-menu-item-icon octicon octicon-check"></span>
              <a href="/amazonwebservices/aws-sdk-for-php/tree/1.5.0.1/_compatibility_test/sdk_compatibility_test.php"
                 data-name="1.5.0.1"
                 data-skip-pjax="true"
                 rel="nofollow"
                 class="js-navigation-open select-menu-item-text css-truncate-target"
                 title="1.5.0.1">1.5.0.1</a>
            </div> <!-- /.select-menu-item -->
            <div class="select-menu-item js-navigation-item ">
              <span class="select-menu-item-icon octicon octicon-check"></span>
              <a href="/amazonwebservices/aws-sdk-for-php/tree/1.5/_compatibility_test/sdk_compatibility_test.php"
                 data-name="1.5"
                 data-skip-pjax="true"
                 rel="nofollow"
                 class="js-navigation-open select-menu-item-text css-truncate-target"
                 title="1.5">1.5</a>
            </div> <!-- /.select-menu-item -->
            <div class="select-menu-item js-navigation-item ">
              <span class="select-menu-item-icon octicon octicon-check"></span>
              <a href="/amazonwebservices/aws-sdk-for-php/tree/1.4.8.1/_compatibility_test/sdk_compatibility_test.php"
                 data-name="1.4.8.1"
                 data-skip-pjax="true"
                 rel="nofollow"
                 class="js-navigation-open select-menu-item-text css-truncate-target"
                 title="1.4.8.1">1.4.8.1</a>
            </div> <!-- /.select-menu-item -->
            <div class="select-menu-item js-navigation-item ">
              <span class="select-menu-item-icon octicon octicon-check"></span>
              <a href="/amazonwebservices/aws-sdk-for-php/tree/1.4.8/_compatibility_test/sdk_compatibility_test.php"
                 data-name="1.4.8"
                 data-skip-pjax="true"
                 rel="nofollow"
                 class="js-navigation-open select-menu-item-text css-truncate-target"
                 title="1.4.8">1.4.8</a>
            </div> <!-- /.select-menu-item -->
            <div class="select-menu-item js-navigation-item ">
              <span class="select-menu-item-icon octicon octicon-check"></span>
              <a href="/amazonwebservices/aws-sdk-for-php/tree/1.4.7/_compatibility_test/sdk_compatibility_test.php"
                 data-name="1.4.7"
                 data-skip-pjax="true"
                 rel="nofollow"
                 class="js-navigation-open select-menu-item-text css-truncate-target"
                 title="1.4.7">1.4.7</a>
            </div> <!-- /.select-menu-item -->
            <div class="select-menu-item js-navigation-item ">
              <span class="select-menu-item-icon octicon octicon-check"></span>
              <a href="/amazonwebservices/aws-sdk-for-php/tree/1.4.6.1/_compatibility_test/sdk_compatibility_test.php"
                 data-name="1.4.6.1"
                 data-skip-pjax="true"
                 rel="nofollow"
                 class="js-navigation-open select-menu-item-text css-truncate-target"
                 title="1.4.6.1">1.4.6.1</a>
            </div> <!-- /.select-menu-item -->
            <div class="select-menu-item js-navigation-item ">
              <span class="select-menu-item-icon octicon octicon-check"></span>
              <a href="/amazonwebservices/aws-sdk-for-php/tree/1.4.6/_compatibility_test/sdk_compatibility_test.php"
                 data-name="1.4.6"
                 data-skip-pjax="true"
                 rel="nofollow"
                 class="js-navigation-open select-menu-item-text css-truncate-target"
                 title="1.4.6">1.4.6</a>
            </div> <!-- /.select-menu-item -->
            <div class="select-menu-item js-navigation-item ">
              <span class="select-menu-item-icon octicon octicon-check"></span>
              <a href="/amazonwebservices/aws-sdk-for-php/tree/1.4.5/_compatibility_test/sdk_compatibility_test.php"
                 data-name="1.4.5"
                 data-skip-pjax="true"
                 rel="nofollow"
                 class="js-navigation-open select-menu-item-text css-truncate-target"
                 title="1.4.5">1.4.5</a>
            </div> <!-- /.select-menu-item -->
            <div class="select-menu-item js-navigation-item ">
              <span class="select-menu-item-icon octicon octicon-check"></span>
              <a href="/amazonwebservices/aws-sdk-for-php/tree/1.4.4/_compatibility_test/sdk_compatibility_test.php"
                 data-name="1.4.4"
                 data-skip-pjax="true"
                 rel="nofollow"
                 class="js-navigation-open select-menu-item-text css-truncate-target"
                 title="1.4.4">1.4.4</a>
            </div> <!-- /.select-menu-item -->
            <div class="select-menu-item js-navigation-item ">
              <span class="select-menu-item-icon octicon octicon-check"></span>
              <a href="/amazonwebservices/aws-sdk-for-php/tree/1.4.3/_compatibility_test/sdk_compatibility_test.php"
                 data-name="1.4.3"
                 data-skip-pjax="true"
                 rel="nofollow"
                 class="js-navigation-open select-menu-item-text css-truncate-target"
                 title="1.4.3">1.4.3</a>
            </div> <!-- /.select-menu-item -->
            <div class="select-menu-item js-navigation-item ">
              <span class="select-menu-item-icon octicon octicon-check"></span>
              <a href="/amazonwebservices/aws-sdk-for-php/tree/1.4.2.1/_compatibility_test/sdk_compatibility_test.php"
                 data-name="1.4.2.1"
                 data-skip-pjax="true"
                 rel="nofollow"
                 class="js-navigation-open select-menu-item-text css-truncate-target"
                 title="1.4.2.1">1.4.2.1</a>
            </div> <!-- /.select-menu-item -->
            <div class="select-menu-item js-navigation-item ">
              <span class="select-menu-item-icon octicon octicon-check"></span>
              <a href="/amazonwebservices/aws-sdk-for-php/tree/1.4.2/_compatibility_test/sdk_compatibility_test.php"
                 data-name="1.4.2"
                 data-skip-pjax="true"
                 rel="nofollow"
                 class="js-navigation-open select-menu-item-text css-truncate-target"
                 title="1.4.2">1.4.2</a>
            </div> <!-- /.select-menu-item -->
            <div class="select-menu-item js-navigation-item ">
              <span class="select-menu-item-icon octicon octicon-check"></span>
              <a href="/amazonwebservices/aws-sdk-for-php/tree/1.4.1/_compatibility_test/sdk_compatibility_test.php"
                 data-name="1.4.1"
                 data-skip-pjax="true"
                 rel="nofollow"
                 class="js-navigation-open select-menu-item-text css-truncate-target"
                 title="1.4.1">1.4.1</a>
            </div> <!-- /.select-menu-item -->
            <div class="select-menu-item js-navigation-item ">
              <span class="select-menu-item-icon octicon octicon-check"></span>
              <a href="/amazonwebservices/aws-sdk-for-php/tree/1.4.0.1/_compatibility_test/sdk_compatibility_test.php"
                 data-name="1.4.0.1"
                 data-skip-pjax="true"
                 rel="nofollow"
                 class="js-navigation-open select-menu-item-text css-truncate-target"
                 title="1.4.0.1">1.4.0.1</a>
            </div> <!-- /.select-menu-item -->
            <div class="select-menu-item js-navigation-item ">
              <span class="select-menu-item-icon octicon octicon-check"></span>
              <a href="/amazonwebservices/aws-sdk-for-php/tree/1.4/_compatibility_test/sdk_compatibility_test.php"
                 data-name="1.4"
                 data-skip-pjax="true"
                 rel="nofollow"
                 class="js-navigation-open select-menu-item-text css-truncate-target"
                 title="1.4">1.4</a>
            </div> <!-- /.select-menu-item -->
            <div class="select-menu-item js-navigation-item ">
              <span class="select-menu-item-icon octicon octicon-check"></span>
              <a href="/amazonwebservices/aws-sdk-for-php/tree/1.3.7/_compatibility_test/sdk_compatibility_test.php"
                 data-name="1.3.7"
                 data-skip-pjax="true"
                 rel="nofollow"
                 class="js-navigation-open select-menu-item-text css-truncate-target"
                 title="1.3.7">1.3.7</a>
            </div> <!-- /.select-menu-item -->
            <div class="select-menu-item js-navigation-item ">
              <span class="select-menu-item-icon octicon octicon-check"></span>
              <a href="/amazonwebservices/aws-sdk-for-php/tree/1.3.6/_compatibility_test/sdk_compatibility_test.php"
                 data-name="1.3.6"
                 data-skip-pjax="true"
                 rel="nofollow"
                 class="js-navigation-open select-menu-item-text css-truncate-target"
                 title="1.3.6">1.3.6</a>
            </div> <!-- /.select-menu-item -->
            <div class="select-menu-item js-navigation-item ">
              <span class="select-menu-item-icon octicon octicon-check"></span>
              <a href="/amazonwebservices/aws-sdk-for-php/tree/1.3.5/_compatibility_test/sdk_compatibility_test.php"
                 data-name="1.3.5"
                 data-skip-pjax="true"
                 rel="nofollow"
                 class="js-navigation-open select-menu-item-text css-truncate-target"
                 title="1.3.5">1.3.5</a>
            </div> <!-- /.select-menu-item -->
            <div class="select-menu-item js-navigation-item ">
              <span class="select-menu-item-icon octicon octicon-check"></span>
              <a href="/amazonwebservices/aws-sdk-for-php/tree/1.3.4/_compatibility_test/sdk_compatibility_test.php"
                 data-name="1.3.4"
                 data-skip-pjax="true"
                 rel="nofollow"
                 class="js-navigation-open select-menu-item-text css-truncate-target"
                 title="1.3.4">1.3.4</a>
            </div> <!-- /.select-menu-item -->
            <div class="select-menu-item js-navigation-item ">
              <span class="select-menu-item-icon octicon octicon-check"></span>
              <a href="/amazonwebservices/aws-sdk-for-php/tree/1.3.3/_compatibility_test/sdk_compatibility_test.php"
                 data-name="1.3.3"
                 data-skip-pjax="true"
                 rel="nofollow"
                 class="js-navigation-open select-menu-item-text css-truncate-target"
                 title="1.3.3">1.3.3</a>
            </div> <!-- /.select-menu-item -->
            <div class="select-menu-item js-navigation-item ">
              <span class="select-menu-item-icon octicon octicon-check"></span>
              <a href="/amazonwebservices/aws-sdk-for-php/tree/1.3.2/_compatibility_test/sdk_compatibility_test.php"
                 data-name="1.3.2"
                 data-skip-pjax="true"
                 rel="nofollow"
                 class="js-navigation-open select-menu-item-text css-truncate-target"
                 title="1.3.2">1.3.2</a>
            </div> <!-- /.select-menu-item -->
            <div class="select-menu-item js-navigation-item ">
              <span class="select-menu-item-icon octicon octicon-check"></span>
              <a href="/amazonwebservices/aws-sdk-for-php/tree/1.3.1/_compatibility_test/sdk_compatibility_test.php"
                 data-name="1.3.1"
                 data-skip-pjax="true"
                 rel="nofollow"
                 class="js-navigation-open select-menu-item-text css-truncate-target"
                 title="1.3.1">1.3.1</a>
            </div> <!-- /.select-menu-item -->
            <div class="select-menu-item js-navigation-item ">
              <span class="select-menu-item-icon octicon octicon-check"></span>
              <a href="/amazonwebservices/aws-sdk-for-php/tree/1.3/_compatibility_test/sdk_compatibility_test.php"
                 data-name="1.3"
                 data-skip-pjax="true"
                 rel="nofollow"
                 class="js-navigation-open select-menu-item-text css-truncate-target"
                 title="1.3">1.3</a>
            </div> <!-- /.select-menu-item -->
            <div class="select-menu-item js-navigation-item ">
              <span class="select-menu-item-icon octicon octicon-check"></span>
              <a href="/amazonwebservices/aws-sdk-for-php/tree/1.2.6/_compatibility_test/sdk_compatibility_test.php"
                 data-name="1.2.6"
                 data-skip-pjax="true"
                 rel="nofollow"
                 class="js-navigation-open select-menu-item-text css-truncate-target"
                 title="1.2.6">1.2.6</a>
            </div> <!-- /.select-menu-item -->
            <div class="select-menu-item js-navigation-item ">
              <span class="select-menu-item-icon octicon octicon-check"></span>
              <a href="/amazonwebservices/aws-sdk-for-php/tree/1.2.5/_compatibility_test/sdk_compatibility_test.php"
                 data-name="1.2.5"
                 data-skip-pjax="true"
                 rel="nofollow"
                 class="js-navigation-open select-menu-item-text css-truncate-target"
                 title="1.2.5">1.2.5</a>
            </div> <!-- /.select-menu-item -->
            <div class="select-menu-item js-navigation-item ">
              <span class="select-menu-item-icon octicon octicon-check"></span>
              <a href="/amazonwebservices/aws-sdk-for-php/tree/1.2.4/_compatibility_test/sdk_compatibility_test.php"
                 data-name="1.2.4"
                 data-skip-pjax="true"
                 rel="nofollow"
                 class="js-navigation-open select-menu-item-text css-truncate-target"
                 title="1.2.4">1.2.4</a>
            </div> <!-- /.select-menu-item -->
            <div class="select-menu-item js-navigation-item ">
              <span class="select-menu-item-icon octicon octicon-check"></span>
              <a href="/amazonwebservices/aws-sdk-for-php/tree/1.2.3/_compatibility_test/sdk_compatibility_test.php"
                 data-name="1.2.3"
                 data-skip-pjax="true"
                 rel="nofollow"
                 class="js-navigation-open select-menu-item-text css-truncate-target"
                 title="1.2.3">1.2.3</a>
            </div> <!-- /.select-menu-item -->
            <div class="select-menu-item js-navigation-item ">
              <span class="select-menu-item-icon octicon octicon-check"></span>
              <a href="/amazonwebservices/aws-sdk-for-php/tree/1.2.2/_compatibility_test/sdk_compatibility_test.php"
                 data-name="1.2.2"
                 data-skip-pjax="true"
                 rel="nofollow"
                 class="js-navigation-open select-menu-item-text css-truncate-target"
                 title="1.2.2">1.2.2</a>
            </div> <!-- /.select-menu-item -->
            <div class="select-menu-item js-navigation-item ">
              <span class="select-menu-item-icon octicon octicon-check"></span>
              <a href="/amazonwebservices/aws-sdk-for-php/tree/1.2.1/_compatibility_test/sdk_compatibility_test.php"
                 data-name="1.2.1"
                 data-skip-pjax="true"
                 rel="nofollow"
                 class="js-navigation-open select-menu-item-text css-truncate-target"
                 title="1.2.1">1.2.1</a>
            </div> <!-- /.select-menu-item -->
            <div class="select-menu-item js-navigation-item ">
              <span class="select-menu-item-icon octicon octicon-check"></span>
              <a href="/amazonwebservices/aws-sdk-for-php/tree/1.2/_compatibility_test/sdk_compatibility_test.php"
                 data-name="1.2"
                 data-skip-pjax="true"
                 rel="nofollow"
                 class="js-navigation-open select-menu-item-text css-truncate-target"
                 title="1.2">1.2</a>
            </div> <!-- /.select-menu-item -->
            <div class="select-menu-item js-navigation-item ">
              <span class="select-menu-item-icon octicon octicon-check"></span>
              <a href="/amazonwebservices/aws-sdk-for-php/tree/1.1/_compatibility_test/sdk_compatibility_test.php"
                 data-name="1.1"
                 data-skip-pjax="true"
                 rel="nofollow"
                 class="js-navigation-open select-menu-item-text css-truncate-target"
                 title="1.1">1.1</a>
            </div> <!-- /.select-menu-item -->
            <div class="select-menu-item js-navigation-item ">
              <span class="select-menu-item-icon octicon octicon-check"></span>
              <a href="/amazonwebservices/aws-sdk-for-php/tree/1.0.1/_compatibility_test/sdk_compatibility_test.php"
                 data-name="1.0.1"
                 data-skip-pjax="true"
                 rel="nofollow"
                 class="js-navigation-open select-menu-item-text css-truncate-target"
                 title="1.0.1">1.0.1</a>
            </div> <!-- /.select-menu-item -->
            <div class="select-menu-item js-navigation-item ">
              <span class="select-menu-item-icon octicon octicon-check"></span>
              <a href="/amazonwebservices/aws-sdk-for-php/tree/1.0/_compatibility_test/sdk_compatibility_test.php"
                 data-name="1.0"
                 data-skip-pjax="true"
                 rel="nofollow"
                 class="js-navigation-open select-menu-item-text css-truncate-target"
                 title="1.0">1.0</a>
            </div> <!-- /.select-menu-item -->
        </div>

        <div class="select-menu-no-results">Nothing to show</div>
      </div> <!-- /.select-menu-list -->

    </div> <!-- /.select-menu-modal -->
  </div> <!-- /.select-menu-modal-holder -->
</div> <!-- /.select-menu -->

  <div class="button-group right">
    <a href="/amazonwebservices/aws-sdk-for-php/find/master"
          class="js-show-file-finder minibutton empty-icon tooltipped tooltipped-s"
          data-pjax
          data-hotkey="t"
          aria-label="Quickly jump between files">
      <span class="octicon octicon-list-unordered"></span>
    </a>
    <button aria-label="Copy file path to clipboard" class="js-zeroclipboard minibutton zeroclipboard-button" data-copied-hint="Copied!" type="button"><span class="octicon octicon-clippy"></span></button>
  </div>

  <div class="breadcrumb js-zeroclipboard-target">
    <span class='repo-root js-repo-root'><span itemscope="" itemtype="http://data-vocabulary.org/Breadcrumb"><a href="/amazonwebservices/aws-sdk-for-php" class="" data-branch="master" data-direction="back" data-pjax="true" itemscope="url"><span itemprop="title">aws-sdk-for-php</span></a></span></span><span class="separator">/</span><span itemscope="" itemtype="http://data-vocabulary.org/Breadcrumb"><a href="/amazonwebservices/aws-sdk-for-php/tree/master/_compatibility_test" class="" data-branch="master" data-direction="back" data-pjax="true" itemscope="url"><span itemprop="title">_compatibility_test</span></a></span><span class="separator">/</span><strong class="final-path">sdk_compatibility_test.php</strong>
  </div>
</div>


  <div class="commit file-history-tease">
    <div class="file-history-tease-header">
        <img alt="Ryan Parman" class="avatar" data-user="39447" height="24" src="https://avatars3.githubusercontent.com/u/39447?v=3&amp;s=48" width="24" />
        <span class="author"><a href="/skyzyx" rel="contributor">skyzyx</a></span>
        <time datetime="2012-04-20T00:16:31Z" is="relative-time">Apr 19, 2012</time>
        <div class="commit-title">
            <a href="/amazonwebservices/aws-sdk-for-php/commit/2674cf052777a196125bf78fe779eec618ca34a9" class="message" data-pjax="true" title="1.5.4 release. http://aws.amazon.com/releasenotes/1700816143828387">1.5.4 release.</a> <a href="http://aws.amazon.com/releasenotes/1700816143828387">http://aws.amazon.com/releasenotes/1700816143828387</a>
        </div>
    </div>

    <div class="participation">
      <p class="quickstat">
        <a href="#blob_contributors_box" rel="facebox">
          <strong>1</strong>
           contributor
        </a>
      </p>
      
    </div>
    <div id="blob_contributors_box" style="display:none">
      <h2 class="facebox-header">Users who have contributed to this file</h2>
      <ul class="facebox-user-list">
          <li class="facebox-user-list-item">
            <img alt="Ryan Parman" data-user="39447" height="24" src="https://avatars3.githubusercontent.com/u/39447?v=3&amp;s=48" width="24" />
            <a href="/skyzyx">skyzyx</a>
          </li>
      </ul>
    </div>
  </div>

<div class="file-box">
  <div class="file">
    <div class="meta clearfix">
      <div class="info file-name">
          <span>790 lines (720 sloc)</span>
          <span class="meta-divider"></span>
        <span>41.341 kb</span>
      </div>
      <div class="actions">
        <div class="button-group">
          <a href="/amazonwebservices/aws-sdk-for-php/raw/master/_compatibility_test/sdk_compatibility_test.php" class="minibutton " id="raw-url">Raw</a>
            <a href="/amazonwebservices/aws-sdk-for-php/blame/master/_compatibility_test/sdk_compatibility_test.php" class="minibutton js-update-url-with-hash">Blame</a>
          <a href="/amazonwebservices/aws-sdk-for-php/commits/master/_compatibility_test/sdk_compatibility_test.php" class="minibutton " rel="nofollow">History</a>
        </div><!-- /.button-group -->

          <a class="octicon-button tooltipped tooltipped-nw js-conduit-openfile-check"
             href="http://mac.github.com"
             data-url="github-mac://openRepo/https://github.com/amazonwebservices/aws-sdk-for-php?branch=master&amp;filepath=_compatibility_test%2Fsdk_compatibility_test.php"
             aria-label="Open this file in GitHub for Mac"
             data-failed-title="Your version of GitHub for Mac is too old to open this file. Try checking for updates.">
              <span class="octicon octicon-device-desktop"></span>
          </a>

            <a class="octicon-button disabled tooltipped tooltipped-w" href="#"
               aria-label="You must be signed in to make or propose changes"><span class="octicon octicon-pencil"></span></a>

          <a class="octicon-button danger disabled tooltipped tooltipped-w" href="#"
             aria-label="You must be signed in to make or propose changes">
          <span class="octicon octicon-trashcan"></span>
        </a>
      </div><!-- /.actions -->
    </div>
    

  <div class="blob-wrapper data type-php">
      <table class="highlight tab-size-8 js-file-line-container">
      <tr>
        <td id="L1" class="blob-num js-line-number" data-line-number="1"></td>
        <td id="LC1" class="blob-code js-file-line"><span class="pl-pse">&lt;?php</span><span class="pl-s2"></span></td>
      </tr>
      <tr>
        <td id="L2" class="blob-num js-line-number" data-line-number="2"></td>
        <td id="LC2" class="blob-code js-file-line"><span class="pl-s2"><span class="pl-k">if</span> (<span class="pl-s3">isset</span>(<span class="pl-vo">$_GET</span>[<span class="pl-s1"><span class="pl-pds">&#39;</span>logopng<span class="pl-pds">&#39;</span></span>]))</span></td>
      </tr>
      <tr>
        <td id="L3" class="blob-num js-line-number" data-line-number="3"></td>
        <td id="LC3" class="blob-code js-file-line"><span class="pl-s2">{</span></td>
      </tr>
      <tr>
        <td id="L4" class="blob-num js-line-number" data-line-number="4"></td>
        <td id="LC4" class="blob-code js-file-line"><span class="pl-s2">	<span class="pl-vo">$data</span> <span class="pl-k">=</span> <span class="pl-s1"><span class="pl-pds">&lt;&lt;&lt;</span><span class="pl-k">IMAGE</span></span></span></td>
      </tr>
      <tr>
        <td id="L5" class="blob-num js-line-number" data-line-number="5"></td>
        <td id="LC5" class="blob-code js-file-line"><span class="pl-s2"><span class="pl-s1">iVBORw0KGgoAAAANSUhEUgAAASwAAABwCAYAAACkRk1NAAAABHNCSVQICAgIfAhkiAAAAAlwSFlz</span></span></td>
      </tr>
      <tr>
        <td id="L6" class="blob-num js-line-number" data-line-number="6"></td>
        <td id="LC6" class="blob-code js-file-line"><span class="pl-s2"><span class="pl-s1">AAALEgAACxIB0t1+/AAAABx0RVh0U29mdHdhcmUAQWRvYmUgRmlyZXdvcmtzIENTNXG14zYAAAAU</span></span></td>
      </tr>
      <tr>
        <td id="L7" class="blob-num js-line-number" data-line-number="7"></td>
        <td id="LC7" class="blob-code js-file-line"><span class="pl-s2"><span class="pl-s1">dEVYdENyZWF0aW9uIFRpbWUAOS80LzEwZyhWjQAAGJpJREFUeNrtnQ2wXEWVgJvwjKksP2MKMAKV</span></span></td>
      </tr>
      <tr>
        <td id="L8" class="blob-num js-line-number" data-line-number="8"></td>
        <td id="LC8" class="blob-code js-file-line"><span class="pl-s2"><span class="pl-s1">HXbZIqUgU1CLWBtkLLZc1IU3CK4ssDAEFVlhM7qIyhYwiqALCqOFIkbNGDSsSszsqllA1zfhT0SX</span></span></td>
      </tr>
      <tr>
        <td id="L9" class="blob-num js-line-number" data-line-number="9"></td>
        <td id="LC9" class="blob-code js-file-line"><span class="pl-s2"><span class="pl-s1">DD8aFgJv+DU/JG9CfpYErNluc+6m35m+ffvvvrkz75yqU8mbudO3b9/u755z+txuxkhISEhISEhI</span></span></td>
      </tr>
      <tr>
        <td id="L10" class="blob-num js-line-number" data-line-number="10"></td>
        <td id="LC10" class="blob-code js-file-line"><span class="pl-s2"><span class="pl-s1">SEgyJa8tnlvgWgUtZqxuOa4lqFtF1JXuGAnJ9AJUHgZ/g2uHa1ehTYBEoQ/1K3KtcW3F1K3Ntc61</span></span></td>
      </tr>
      <tr>
        <td id="L11" class="blob-num js-line-number" data-line-number="11"></td>
        <td id="LC11" class="blob-code js-file-line"><span class="pl-s2"><span class="pl-s1">LK6F7igJyfABqgyDvB0DAZ12AG6VNAABFl4FINl10BYATlhiObrjJCSDBajIjdJZKT4qWzg5h/qZ</span></span></td>
      </tr>
      <tr>
        <td id="L12" class="blob-num js-line-number" data-line-number="12"></td>
        <td id="LC12" class="blob-code js-file-line"><span class="pl-s2"><span class="pl-s1">WHg+2syie0tCQqKGwYoUIBCnq23AAKBaPUV1mwBok9VFQjIAVtZ5AK+JwCAY47rIxz0EsC5KAV7i</span></span></td>
      </tr>
      <tr>
        <td id="L13" class="blob-num js-line-number" data-line-number="13"></td>
        <td id="LC13" class="blob-code js-file-line"><span class="pl-s2"><span class="pl-s1">WpdwHaVeQEIyuAAbhYHsCq8VAMBcCnXzhdc4QYqEZHBg1IR4kFFMyQJexpCCmJmIa3XQ50WIedVM</span></span></td>
      </tr>
      <tr>
        <td id="L14" class="blob-num js-line-number" data-line-number="14"></td>
        <td id="LC14" class="blob-code js-file-line"><span class="pl-s2"><span class="pl-s1">Zhwt4CUgdaNFmVFQv0Y9hoSk/8CSB3PDdMpfgte4DaTA5YxmHicFzhXAUgXsS5ZurSuk8KRDk3oM</span></span></td>
      </tr>
      <tr>
        <td id="L15" class="blob-num js-line-number" data-line-number="15"></td>
        <td id="LC15" class="blob-code js-file-line"><span class="pl-s2"><span class="pl-s1">CUm2gIWn/IOkI0iQauisnwRg4XQJI3gZ1q9gMDNKwCIhyTCwVPAqWJSdN4GUI7BUuV5WqRISpEzz</span></span></td>
      </tr>
      <tr>
        <td id="L16" class="blob-num js-line-number" data-line-number="16"></td>
        <td id="LC16" class="blob-code js-file-line"><span class="pl-s2"><span class="pl-s1">ywhYJCQDAizsminjShp3Kk1gdWPc2pwmXuaSBEvAIiEZQGDJWkTleaUWBAKWEjCQEBqsPBISEgIW</span></span></td>
      </tr>
      <tr>
        <td id="L17" class="blob-num js-line-number" data-line-number="17"></td>
        <td id="LC17" class="blob-code js-file-line"><span class="pl-s2"><span class="pl-s1">AYuEhISARcAiISFgEbBISEgIWAQsEhISApa7UqY7CQkBa2CAVaUeQ0JCwCJgkZCQELAIWCQkBCwC</span></span></td>
      </tr>
      <tr>
        <td id="L18" class="blob-num js-line-number" data-line-number="18"></td>
        <td id="LC18" class="blob-code js-file-line"><span class="pl-s2"><span class="pl-s1">FgkJCQGLgEVCQkLAImCRkBCwCFgkJCQELAIWCQkJAYuARUJCwCJgkZCQELAIWCQkJNkE1jgqq+C5</span></span></td>
      </tr>
      <tr>
        <td id="L19" class="blob-num js-line-number" data-line-number="19"></td>
        <td id="LC19" class="blob-code js-file-line"><span class="pl-s2"><span class="pl-s1">L2JoYFWox5CQ9BdYJc/dnn2Blbibjce+iCGAtdp341cSEpLw4MLbYqUJLOMttzzh5QqsFQQpEpLh</span></span></td>
      </tr>
      <tr>
        <td id="L20" class="blob-num js-line-number" data-line-number="20"></td>
        <td id="LC20" class="blob-code js-file-line"><span class="pl-s2"><span class="pl-s1">hJcpsJwh5QEvG2Cltjs1CUnWB3wRD+SM1a8AdcwFgJcOWMEhZQmvJGCNEaRIpqtFUlLsZOy0X16K</span></span></td>
      </tr>
      <tr>
        <td id="L21" class="blob-num js-line-number" data-line-number="21"></td>
        <td id="LC21" class="blob-code js-file-line"><span class="pl-s2"><span class="pl-s1">sSrVHnwN081SJXit1gBrDMBR7OO1jgJgawpgUUyKZFpCKtp/r2ETBA61k7IhXKJNTDsWm6VWLbZz</span></span></td>
      </tr>
      <tr>
        <td id="L22" class="blob-num js-line-number" data-line-number="22"></td>
        <td id="LC22" class="blob-code js-file-line"><span class="pl-s2"><span class="pl-s1">p4FPQpJxV6rqukmo6WakASDaDFS/YFvBk5CQ9NeVCqkdVzg4bLfuWr9MuLYkJCR6GNwIweNuyhoF</span></span></td>
      </tr>
      <tr>
        <td id="L23" class="blob-num js-line-number" data-line-number="23"></td>
        <td id="LC23" class="blob-code js-file-line"><span class="pl-s2"><span class="pl-s1">qE+0tKgWoZhSWjoBsalR6hkkJIMBr9BwCDaLlhK8CFIkJEMALx84GEPKNaAN9TvPMYPd2NKDwH5q</span></span></td>
      </tr>
      <tr>
        <td id="L24" class="blob-num js-line-number" data-line-number="24"></td>
        <td id="LC24" class="blob-code js-file-line"><span class="pl-s2"><span class="pl-s1">LuLIjL1zXItcK1yrSMXn+am87+J8cF6hBYffR78t9qG9SlPdXinfi6LPvQhUh4JUh3y/YGQVEDeE</span></span></td>
      </tr>
      <tr>
        <td id="L25" class="blob-num js-line-number" data-line-number="25"></td>
        <td id="LC25" class="blob-code js-file-line"><span class="pl-s2"><span class="pl-s1">l/juKsPyJgX2Fd83bWJehkmgVhCFwH5LldYQCApigLW4dg20DcfnLQd2VaM5xbFtxbk7XOvy8TED</span></span></td>
      </tr>
      <tr>
        <td id="L26" class="blob-num js-line-number" data-line-number="26"></td>
        <td id="LC26" class="blob-code js-file-line"><span class="pl-s2"><span class="pl-s1">qxFTd3GN5QCQqli2V812gME5mgG1YQMa6Be1hOtsQj1zDuCJ6wsVRT3qcO/j+kN+qoGlms0rGQ7m</span></span></td>
      </tr>
      <tr>
        <td id="L27" class="blob-num js-line-number" data-line-number="27"></td>
        <td id="LC27" class="blob-code js-file-line"><span class="pl-s2"><span class="pl-s1">CA5jptP+usB5DLCcAvYIXmMekIpNHPUceDXDQRenVYunc2I50JE7Buft4MEH19MwrHfL1kqQ2quT</span></span></td>
      </tr>
      <tr>
        <td id="L28" class="blob-num js-line-number" data-line-number="28"></td>
        <td id="LC28" class="blob-code js-file-line"><span class="pl-s2"><span class="pl-s1">dnvB+bopaDWlftHBoEk4h/Y+oX5j2h/K/QJWkNk8xXmKJrN7BsDqKhJBvWfzLGYfiwFgVYixYFy0</span></span></td>
      </tr>
      <tr>
        <td id="L29" class="blob-num js-line-number" data-line-number="29"></td>
        <td id="LC29" class="blob-code js-file-line"><span class="pl-s2"><span class="pl-s1">HgJYFrDqgRYMspbDIMtbtFcrUHu1TCySfgArQL9oGl5bMwlYUBfb85f6DSwVvIzhEJMN3w0MLGd4</span></span></td>
      </tr>
      <tr>
        <td id="L30" class="blob-num js-line-number" data-line-number="30"></td>
        <td id="LC30" class="blob-code js-file-line"><span class="pl-s2"><span class="pl-s1">OaZIFD1hlfe0ElRaCwCspsvgh/JdLcWmocURur3qWQOWwwPDGciGwHIBZ8fWPU0TWEZwcIFUQGCp</span></span></td>
      </tr>
      <tr>
        <td id="L31" class="blob-num js-line-number" data-line-number="31"></td>
        <td id="LC31" class="blob-code js-file-line"><span class="pl-s2"><span class="pl-s1">suyD1i8AsFopDYaCB7B8nupVz3oXfQZXiuedMmA5Wqg6bXi2acXj3JWsAiv1FTg9gZVK/XyAJfz8</span></span></td>
      </tr>
      <tr>
        <td id="L32" class="blob-num js-line-number" data-line-number="32"></td>
        <td id="LC32" class="blob-code js-file-line"><span class="pl-s2"><span class="pl-s1">lAaC1mowAFY/taapd6kf7dUHYNVSOFfFA1g+D7AWAWt4gNUytFjyCDYmv2sPKLCantZVDbVXwfB3</span></span></td>
      </tr>
      <tr>
        <td id="L33" class="blob-num js-line-number" data-line-number="33"></td>
        <td id="LC33" class="blob-code js-file-line"><span class="pl-s2"><span class="pl-s1">HZ/AtKO7lI8JERgFtaW0gqqB+xjrnqVotU4K2hOwBhhYhh2zrHEZ2q4dxRJYdRgQNccnbfR70wHf</span></span></td>
      </tr>
      <tr>
        <td id="L34" class="blob-num js-line-number" data-line-number="34"></td>
        <td id="LC34" class="blob-code js-file-line"><span class="pl-s2"><span class="pl-s1">0VyzU3tZPBy84y0W7lMl5vd1F3ffMCheDQCshpTy0A7hbhOwBgNYRZ8AtGGsKOcJrIJHbKWFLQjT</span></span></td>
      </tr>
      <tr>
        <td id="L35" class="blob-num js-line-number" data-line-number="35"></td>
        <td id="LC35" class="blob-code js-file-line"><span class="pl-s2"><span class="pl-s1">maaU2quc9sCymEBpekBZ5zLXXaxuC2CVHfsDAWtILKyWi7VgAZ2ix2/rnnG3gqsF4dhelbTaK7CL</span></span></td>
      </tr>
      <tr>
        <td id="L36" class="blob-num js-line-number" data-line-number="36"></td>
        <td id="LC36" class="blob-code js-file-line"><span class="pl-s2"><span class="pl-s1">H5u+Ydi2ec8YX8ERWPV+PQgIWBmJYSkGVPRaSSWAheYLrKJHALrlY+lY5GIVobxU28uwPqazo6XQ</span></span></td>
      </tr>
      <tr>
        <td id="L37" class="blob-num js-line-number" data-line-number="37"></td>
        <td id="LC37" class="blob-code js-file-line"><span class="pl-s2"><span class="pl-s1">FpKlhVZ1BFbBoz9UCVhDBqwUXEovYCWcu+3hthT7EaRNE1gWSZUNz3Y1yVPr2JZhAKyO5zlTBdYE</span></span></td>
      </tr>
      <tr>
        <td id="L38" class="blob-num js-line-number" data-line-number="38"></td>
        <td id="LC38" class="blob-code js-file-line"><span class="pl-s2"><span class="pl-s1">ASs7wFK80FuHDtbqI7CaI+4JkakCS2qvcqj2MjhfeyRAEqVvQrArfAx+00yrP4QAVpOA1T9gQaym</span></span></td>
      </tr>
      <tr>
        <td id="L39" class="blob-num js-line-number" data-line-number="39"></td>
        <td id="LC39" class="blob-code js-file-line"><span class="pl-s2"><span class="pl-s1">ArMxvpnOrsDqDAqwpJefGwFeb3IBVm0kTGJqMYRrZeLeOfymRsAiYKlAVR+ZguztADNufQfWVLZX</span></span></td>
      </tr>
      <tr>
        <td id="L40" class="blob-num js-line-number" data-line-number="40"></td>
        <td id="LC40" class="blob-code js-file-line"><span class="pl-s2"><span class="pl-s1">gPSQWqCyTIBVd5j99QIOAWuaAcvz1YdpByxw9zpT1V4ay65jmN6Rm0JgVW2vk4BFwLKBVX0kvSzj</span></span></td>
      </tr>
      <tr>
        <td id="L41" class="blob-num js-line-number" data-line-number="41"></td>
        <td id="LC41" class="blob-code js-file-line"><span class="pl-s2"><span class="pl-s1">oQNWinC3BVbDJ72DgEXAGuZ3CTsQKylDx86lPEuYSWBZvEsYLSQXpL086lG16AsELAJWphNHTV2K</span></span></td>
      </tr>
      <tr>
        <td id="L42" class="blob-num js-line-number" data-line-number="42"></td>
        <td id="LC42" class="blob-code js-file-line"><span class="pl-s2"><span class="pl-s1">Wh/SGjIHLJv2UrlgoYDlm81OwCJgDSqwTGaXKn3Kw8oisCo+AzogsJojHtnsnrlcIdIauqGBQ8Ca</span></span></td>
      </tr>
      <tr>
        <td id="L43" class="blob-num js-line-number" data-line-number="43"></td>
        <td id="LC43" class="blob-code js-file-line"><span class="pl-s2"><span class="pl-s1">HsBKmoJv9zFxNIvAavWrvRziZ2XHPhFigUMCFgErLLAMV2pIyn0pTzNg+a5nVfIBVqhs9oQ6ekHZ</span></span></td>
      </tr>
      <tr>
        <td id="L44" class="blob-num js-line-number" data-line-number="44"></td>
        <td id="LC44" class="blob-code js-file-line"><span class="pl-s2"><span class="pl-s1">8EHYHDZgVT2z3UMCYUXG67faZSuyEPEKw9nFoQBWoPaqeQLLdQXQDrRXNWmNc8N7mkuIi6bxLmF2</span></span></td>
      </tr>
      <tr>
        <td id="L45" class="blob-num js-line-number" data-line-number="45"></td>
        <td id="LC45" class="blob-code js-file-line"><span class="pl-s2"><span class="pl-s1">gSUN5FHYRHRiioGwAna2yWW0fsa7AaUFLIsAdIWAZWx56NaLqgZMnejEbTNmaAWWPK3uwlACywMO</span></span></td>
      </tr>
      <tr>
        <td id="L46" class="blob-num js-line-number" data-line-number="46"></td>
        <td id="LC46" class="blob-code js-file-line"><span class="pl-s2"><span class="pl-s1">LkAwgpRB/cazWD/HAdjwnAmKXTVhSIHVChB7anvAzkXLDg+ipsd9aacBnMwByxJepkAwhgDsJ5g3</span></span></td>
      </tr>
      <tr>
        <td id="L47" class="blob-num js-line-number" data-line-number="47"></td>
        <td id="LC47" class="blob-code js-file-line"><span class="pl-s2"><span class="pl-s1">rF8B9hscn6r6pQSsuKeh7TrwlSEAlmn8qOjR1jqXKc0loXMObmHZEcrlaQcsA3jpgGADqWjz0qZi</span></span></td>
      </tr>
      <tr>
        <td id="L48" class="blob-num js-line-number" data-line-number="48"></td>
        <td id="LC48" class="blob-code js-file-line"><span class="pl-s2"><span class="pl-s1">U9eCBbxs6jeWFqQcYg3y2t3RWll1RxekMA2C7tG1Vj3bS/WuXZrAKjpMyET5ZkVwI02us+2xpvtw</span></span></td>
      </tr>
      <tr>
        <td id="L49" class="blob-num js-line-number" data-line-number="49"></td>
        <td id="LC49" class="blob-code js-file-line"><span class="pl-s2"><span class="pl-s1">AAtZQNGOygX03ZgFpGz2BbTa1FWCq6p+XjGplGaEUtsRZkCB1ZjC9mr0C1gpxMxMYl/TC1ieoPPd</span></span></td>
      </tr>
      <tr>
        <td id="L50" class="blob-num js-line-number" data-line-number="50"></td>
        <td id="LC50" class="blob-code js-file-line"><span class="pl-s2"><span class="pl-s1">FxDvi5gfhGtPeYuvpFjJIAJrKnf6qfYTWCk80OppAmeogQWuXhkA001JW+BOFjIOLd9OWTaYri8P</span></span></td>
      </tr>
      <tr>
        <td id="L51" class="blob-num js-line-number" data-line-number="51"></td>
        <td id="LC51" class="blob-code js-file-line"><span class="pl-s2"><span class="pl-s1">Qx6W4XlN2qvq0F46NyvaRaYkuaKRqxbtFtRyAFaozVTrBv2QgKWB1ZIAq5ra5EmVMgys3Ij7FuCl</span></span></td>
      </tr>
      <tr>
        <td id="L52" class="blob-num js-line-number" data-line-number="52"></td>
        <td id="LC52" class="blob-code js-file-line"><span class="pl-s2"><span class="pl-s1">BPB1hvDlZ9dB3JFBFFP/TpzbpIBUxeHVm2hhxqbFWum+0Kob1o2AlWKe15SmIEwRtGwsh6Zi+yzc</span></span></td>
      </tr>
      <tr>
        <td id="L53" class="blob-num js-line-number" data-line-number="53"></td>
        <td id="LC53" class="blob-code js-file-line"><span class="pl-s2"><span class="pl-s1">sdueW9X7Aqvsce6OQXvVA7dXJ6G9amApFQPOEtcN3w3MOcS02klJqpbxQV9gVdgwSQB4DRSkNJ24</span></span></td>
      </tr>
      <tr>
        <td id="L54" class="blob-num js-line-number" data-line-number="54"></td>
        <td id="LC54" class="blob-code js-file-line"><span class="pl-s2"><span class="pl-s1">prj5bSlTOulp3ICBkDMYBBVpQ0yshYTfFzS/rRicX3fukmF75aX26ni0V8PWWurjg62isbg6cC1l</span></span></td>
      </tr>
      <tr>
        <td id="L55" class="blob-num js-line-number" data-line-number="55"></td>
        <td id="LC55" class="blob-code js-file-line"><span class="pl-s2"><span class="pl-s1">h7J197NqcD+9fp81GDVtAuIArxsNkkBtUiT+P7DPSEiGQADYUbwsRy0SFliqgLgJvOQk0Amwwkxe</span></span></td>
      </tr>
      <tr>
        <td id="L56" class="blob-num js-line-number" data-line-number="56"></td>
        <td id="LC56" class="blob-code js-file-line"><span class="pl-s2"><span class="pl-s1">28lJgf2O7mVqEhISEh2wnOBlcJ6cyewj3REvmc31QK5voKYgmY7A6rpmsUPZqmz4LgErFbmG6wau</span></span></td>
      </tr>
      <tr>
        <td id="L57" class="blob-num js-line-number" data-line-number="57"></td>
        <td id="LC57" class="blob-code js-file-line"><span class="pl-s2"><span class="pl-s1">W+HfedQkwWQG13O5rgE9m5ok+8AygpcEqVaI9bVIjOXHXLuSvoeaJJj8GddXpLYVD4UjqFkGB1hT</span></span></td>
      </tr>
      <tr>
        <td id="L58" class="blob-num js-line-number" data-line-number="58"></td>
        <td id="LC58" class="blob-code js-file-line"><span class="pl-s2"><span class="pl-s1">tiAgibGsJGClJl9CbSv0FGoWAhYBi4CVRZnDdZPUti+z3fFCEgIWCQErk7If11u4fp2agoBFwCJg</span></span></td>
      </tr>
      <tr>
        <td id="L59" class="blob-num js-line-number" data-line-number="59"></td>
        <td id="LC59" class="blob-code js-file-line"><span class="pl-s2"><span class="pl-s1">kZAQsAZQ5nO9nO0OoovZvse43s71Aq4HOAJLTI6czvVUrkdynRmgngdx/dMArpG4JtGHjuU6bAmW</span></span></td>
      </tr>
      <tr>
        <td id="L60" class="blob-num js-line-number" data-line-number="60"></td>
        <td id="LC60" class="blob-code js-file-line"><span class="pl-s2"><span class="pl-s1">oo1EmsnrPMqYy/VdcN/28ShHTBzMI2ANN7D+iu2exv4t12Wa40a53s11jOtZmuMENO7kegdX/IrL</span></span></td>
      </tr>
      <tr>
        <td id="L61" class="blob-num js-line-number" data-line-number="61"></td>
        <td id="LC61" class="blob-code js-file-line"><span class="pl-s2"><span class="pl-s1">/mx3asILrDfAG+kTXBdaAOtvuK7l2pE+38ZVLFf8dw7tIcq7B+q4GeI6W7g+znU517dw3dugHDHw</span></span></td>
      </tr>
      <tr>
        <td id="L62" class="blob-num js-line-number" data-line-number="62"></td>
        <td id="LC62" class="blob-code js-file-line"><span class="pl-s2"><span class="pl-s1">ylx/zfVZqNME1xe53g9toxvkZ3A9h+vBXPeCzy7m+ijUZ5zrvvD5J7neB/UuG9TtMK5LoT2jd+xE</span></span></td>
      </tr>
      <tr>
        <td id="L63" class="blob-num js-line-number" data-line-number="63"></td>
        <td id="LC63" class="blob-code js-file-line"><span class="pl-s2"><span class="pl-s1">WkMVyl/N9TLN74/i+hVoE9H2T3N9jutd0DdmGcbMLoW+Jx5aO6C918F1HGvYzlWpP70C7bwT6nSd</span></span></td>
      </tr>
      <tr>
        <td id="L64" class="blob-num js-line-number" data-line-number="64"></td>
        <td id="LC64" class="blob-code js-file-line"><span class="pl-s2"><span class="pl-s1">1EZ/lIPnHFDm2kQqPqvB//NwXDH6joCVLfmBNNB3wUBRyf3ScaJTjyiOEZ/9r3TcF6XvZsGA36mB</span></span></td>
      </tr>
      <tr>
        <td id="L65" class="blob-num js-line-number" data-line-number="65"></td>
        <td id="LC65" class="blob-code js-file-line"><span class="pl-s2"><span class="pl-s1">VVfqeGcaAEuU/7KmHHE9N1hYQT/huj2hbuL77yaUdQiAfVdC3e6MsbgE1F+F40R7/gW0HS7v43D8</span></span></td>
      </tr>
      <tr>
        <td id="L66" class="blob-num js-line-number" data-line-number="66"></td>
        <td id="LC66" class="blob-code js-file-line"><span class="pl-s2"><span class="pl-s1">i9JnG8Ha1Mld0vF/gM9yAI2udF4se8EDqYXucxed/46E8x8HD0hdX9iZ0M7HAOiS+tJ6+QELIKpy</span></span></td>
      </tr>
      <tr>
        <td id="L67" class="blob-num js-line-number" data-line-number="67"></td>
        <td id="LC67" class="blob-code js-file-line"><span class="pl-s2"><span class="pl-s1">bYNWJTh1xd9wXF3+m4CVHbkYWSfLYwbgk9Ix68BVwnIydJAuWAGyhXWbooM+A+f7BXR0+TtRp7cl</span></span></td>
      </tr>
      <tr>
        <td id="L68" class="blob-num js-line-number" data-line-number="68"></td>
        <td id="LC68" class="blob-code js-file-line"><span class="pl-s2"><span class="pl-s1">AGtnwt8RGC4zaId7DDq/fJ4vayyH31uU9ZDCfV0qff8S11tjri0C1n3o808ngFmu35gErO3ooYHl</span></span></td>
      </tr>
      <tr>
        <td id="L69" class="blob-num js-line-number" data-line-number="69"></td>
        <td id="LC69" class="blob-code js-file-line"><span class="pl-s2"><span class="pl-s1">eAOgR78dA4say59D3zBtn9sUD8f5YLWaliEs2wXI0vqjdYX+7gDEcgArAlYGgXUkuBfRzX1Rccz7</span></span></td>
      </tr>
      <tr>
        <td id="L70" class="blob-num js-line-number" data-line-number="70"></td>
        <td id="LC70" class="blob-code js-file-line"><span class="pl-s2"><span class="pl-s1">EVCEVXOu4rgrpCfvBohNRE/UHagT3Q6xD9lNeRgd83gCsOSnurBKTgIXB8PvNa6Ha9rgVMVA/B3X</span></span></td>
      </tr>
      <tr>
        <td id="L71" class="blob-num js-line-number" data-line-number="71"></td>
        <td id="LC71" class="blob-code js-file-line"><span class="pl-s2"><span class="pl-s1">xVy/wPU/wV3Bg3Kewg28QwHMu+EcZ8BA3oaOuVYDrC2Kc++Ez6NBuAhZPPdrrvU0qX3ENXzYEFiH</span></span></td>
      </tr>
      <tr>
        <td id="L72" class="blob-num js-line-number" data-line-number="72"></td>
        <td id="LC72" class="blob-code js-file-line"><span class="pl-s2"><span class="pl-s1">Iis76gfiem8GV162dkXi6T8pYNlSPJiEZftutju7/lfIknxVESL4maI9xO++yXbPcD7JJifBdsFl</span></span></td>
      </tr>
      <tr>
        <td id="L73" class="blob-num js-line-number" data-line-number="73"></td>
        <td id="LC73" class="blob-code js-file-line"><span class="pl-s2"><span class="pl-s1">TQJWZFXVAF5NAlY2g+6y9STAkkffq5IK64py5Cf9g1Js5CH027jtsMSgaKO6HJ8ArE0KGB2rAKRu</span></span></td>
      </tr>
      <tr>
        <td id="L74" class="blob-num js-line-number" data-line-number="74"></td>
        <td id="LC74" class="blob-code js-file-line"><span class="pl-s2"><span class="pl-s1">qn4MHbtSEYeZCxYhPk6WhWiwCVBeqDjfpZLLFw3aA2KA9Qfkji6DwS0f/1aweuXBGRd8riNL+WBD</span></span></td>
      </tr>
      <tr>
        <td id="L75" class="blob-num js-line-number" data-line-number="75"></td>
        <td id="LC75" class="blob-code js-file-line"><span class="pl-s2"><span class="pl-s1">YC1ClriwcE5Ex1yPjmmhIPo1CCTbwSrH8m1kUa6VytkXrE45Xnk+ineNQPxvAt2Lv04AlnAPWwCt</span></span></td>
      </tr>
      <tr>
        <td id="L76" class="blob-num js-line-number" data-line-number="76"></td>
        <td id="LC76" class="blob-code js-file-line"><span class="pl-s2"><span class="pl-s1">OgEru8Bagp5WH9bEr2QLZCayLmTL5ibJnXwZxUaO0dTlixowrlRYL+8ziM1FlmNcMPgFdP3HxRz3</span></span></td>
      </tr>
      <tr>
        <td id="L77" class="blob-num js-line-number" data-line-number="77"></td>
        <td id="LC77" class="blob-code js-file-line"><span class="pl-s2"><span class="pl-s1">dmkgiXOvQt//Cp1zVUzgeCbEAeVjL4sBljzg4t7vmwUxIRmA5yiOez2yYtdI3yUB62H03eWK8l+H</span></span></td>
      </tr>
      <tr>
        <td id="L78" class="blob-num js-line-number" data-line-number="78"></td>
        <td id="LC78" class="blob-code js-file-line"><span class="pl-s2"><span class="pl-s1">Hk6bJSDtB1aYfE1LYq5nNopP7YJZxMhal/vZU4bxWdHv3msArAoAq0TAyi6wFqIn479J3x2k6Ghd</span></span></td>
      </tr>
      <tr>
        <td id="L79" class="blob-num js-line-number" data-line-number="79"></td>
        <td id="LC79" class="blob-code js-file-line"><span class="pl-s2"><span class="pl-s1">iIPI75qdInWyrWxPysEV6HfPxgTsI1mABs46DbB+l+DqTiBr7a0xx76EgPXOmONmwGzpVxXHFFBs</span></span></td>
      </tr>
      <tr>
        <td id="L80" class="blob-num js-line-number" data-line-number="80"></td>
        <td id="LC80" class="blob-code js-file-line"><span class="pl-s2"><span class="pl-s1">5hWYTdO5obI1dl8CsFYk3MOvo+OXxwSqn5eO+awhsI5Cv9scE5+KZjfljPnPS/1jK3L1kmb/5Ov5</span></span></td>
      </tr>
      <tr>
        <td id="L81" class="blob-num js-line-number" data-line-number="81"></td>
        <td id="LC81" class="blob-code js-file-line"><span class="pl-s2"><span class="pl-s1">AXw+itrtSQCxSgQk/4XrJyB2JruEBaHo7zzEr4ryZwSs7MnhyBV7nu2ZSh+VgrTbpEEpOvSHUPxK</span></span></td>
      </tr>
      <tr>
        <td id="L82" class="blob-num js-line-number" data-line-number="82"></td>
        <td id="LC82" class="blob-code js-file-line"><span class="pl-s2"><span class="pl-s1">DhRHs18/QR3vFxCwj9N3INB0ARQqYH0l4bqeQ8cvijluHB13vxR/M5V/VABdJ/OQ5bmR7UlzWKqw</span></span></td>
      </tr>
      <tr>
        <td id="L83" class="blob-num js-line-number" data-line-number="83"></td>
        <td id="LC83" class="blob-code js-file-line"><span class="pl-s2"><span class="pl-s1">JJPyi96F2u1Z1pvTdKkU61qHJjV0wKqgGNk9mnrMRX1pKXx+C7qm3yRczwmoPmsk6G5GbXMp88vf</span></span></td>
      </tr>
      <tr>
        <td id="L84" class="blob-num js-line-number" data-line-number="84"></td>
        <td id="LC84" class="blob-code js-file-line"><span class="pl-s2"><span class="pl-s1">ypYQsIzkURRbiAbs51CQVY6V3Cr9/t6YGNXjikDtRo2+hOIX2yAgrwJW0lLGOAC+2CCuI7uQPwdX</span></span></td>
      </tr>
      <tr>
        <td id="L85" class="blob-num js-line-number" data-line-number="85"></td>
        <td id="LC85" class="blob-code js-file-line"><span class="pl-s2"><span class="pl-s1">bQHTJ7QKaSgmDD6o0UuQRbYVYngqYD0tPUCYxi2cQLE9HGNaJX0/jr7TAesbhuCP5GRot49wfTN8</span></span></td>
      </tr>
      <tr>
        <td id="L86" class="blob-num js-line-number" data-line-number="86"></td>
        <td id="LC86" class="blob-code js-file-line"><span class="pl-s2"><span class="pl-s1">9iAq496E9rkStc8mcKVnIgsugtYasMLOAotwNgFruIH1NTR4TofPH5A+F0mQjyCXbBYM5rXS51+K</span></span></td>
      </tr>
      <tr>
        <td id="L87" class="blob-num js-line-number" data-line-number="87"></td>
        <td id="LC87" class="blob-code js-file-line"><span class="pl-s2"><span class="pl-s1">Cei7qADW0THASno151Z0/PdjjjsMYkRxdRDtsR4GnQgcz0+YcHDVY2OAdZvhPfwvjQWKU1OWWQDr</span></span></td>
      </tr>
      <tr>
        <td id="L88" class="blob-num js-line-number" data-line-number="88"></td>
        <td id="LC88" class="blob-code js-file-line"><span class="pl-s2"><span class="pl-s1">TnQ/XHaBesKzbTZBeELIlxOO7YCFK/rLeQNnfRGwjORs9ERbAjMyckBaDNZr0WzU0chtFIP7A1K5</span></span></td>
      </tr>
      <tr>
        <td id="L89" class="blob-num js-line-number" data-line-number="89"></td>
        <td id="LC89" class="blob-code js-file-line"><span class="pl-s2"><span class="pl-s1">L3p21B2SO4SBdWrCNWHLoKE59iLFkztON4AFMDMmKO2qb4sB1rcM7+H5bHLKxNPSd/8gxRi3wD0z</span></span></td>
      </tr>
      <tr>
        <td id="L90" class="blob-num js-line-number" data-line-number="90"></td>
        <td id="LC90" class="blob-code js-file-line"><span class="pl-s2"><span class="pl-s1">Bdbd6NpH+wSsg6XyHjD83S7og0cTsIYLWIdCB49u9CNgwWyQOsxJAKPNUqf+KJj/cpLeG6Vy26gD</span></span></td>
      </tr>
      <tr>
        <td id="L91" class="blob-num js-line-number" data-line-number="91"></td>
        <td id="LC91" class="blob-code js-file-line"><span class="pl-s2"><span class="pl-s1">fQbiE6aa0wTdkyysGwwC0bKcCYDeZTAQXkUxPOz6boRBaqq/kcC8VDODqJP9UWB7o+SSLUEza4dY</span></span></td>
      </tr>
      <tr>
        <td id="L92" class="blob-num js-line-number" data-line-number="92"></td>
        <td id="LC92" class="blob-code js-file-line"><span class="pl-s2"><span class="pl-s1">AKsZAFjPKGJ8bQt9QOHm3YwmTHT6HNPn4mUKWPUMAWs8w031axQ4v5ZNTgTdF2C0Cc3ejKHBMEMq</span></span></td>
      </tr>
      <tr>
        <td id="L93" class="blob-num js-line-number" data-line-number="93"></td>
        <td id="LC93" class="blob-code js-file-line"><span class="pl-s2"><span class="pl-s1">839Qx/m8R/0wsE5POB7Hpr5neB6R+/VVcHm3aQAm7mU0i4QTIm/2uE4MrEssfvtLNnnGU/z2T+Ba</span></span></td>
      </tr>
      <tr>
        <td id="L94" class="blob-num js-line-number" data-line-number="94"></td>
        <td id="LC94" class="blob-code js-file-line"><span class="pl-s2"><span class="pl-s1">cHa7i0u4Faw1W8H94MxAfTYH3sESiMNOsPhXfpazQRFY1ngR7MQ81cAah513Chlvpn9FwfFHFbM6</span></span></td>
      </tr>
      <tr>
        <td id="L95" class="blob-num js-line-number" data-line-number="95"></td>
        <td id="LC95" class="blob-code js-file-line"><span class="pl-s2"><span class="pl-s1">M5B5vxZZZt9AZeJljW8NCKxywvE/Qsdf43je46Bt1ikGwUVwzO3o85/2CVjyTGAXXFXRf6O0BPHd</span></span></td>
      </tr>
      <tr>
        <td id="L96" class="blob-num js-line-number" data-line-number="96"></td>
        <td id="LC96" class="blob-code js-file-line"><span class="pl-s2"><span class="pl-s1">hZbAwjltVyfUQUxSLET35xFUxg0p9eE54AksY71vPKyD7wcuCG8LLxdgDQqkZDkDmdhyIFp+d+6b</span></span></td>
      </tr>
      <tr>
        <td id="L97" class="blob-num js-line-number" data-line-number="97"></td>
        <td id="LC97" class="blob-code js-file-line"><span class="pl-s2"><span class="pl-s1">yO2IYl+iw+MVEq5WWCU6mQ1PSzF1fhMKcGNgfTuhLOymvduzfeaiyQW5Dtcx8xwx2YU7LDCwjlDE</span></span></td>
      </tr>
      <tr>
        <td id="L98" class="blob-num js-line-number" data-line-number="98"></td>
        <td id="LC98" class="blob-code js-file-line"><span class="pl-s2"><span class="pl-s1">DT/H9mT+i0F7pCWwPsV6U1Pi5E1gZa+HvvSjGKD/h8G1HKhwXW3kBIV1/HY2yGIIL1NgDSKkZDmA</span></span></td>
      </tr>
      <tr>
        <td id="L99" class="blob-num js-line-number" data-line-number="99"></td>
        <td id="LC99" class="blob-code js-file-line"><span class="pl-s2"><span class="pl-s1">TU4QjF6jWI8C6adJkNqBXBC8+oBoCzkpdTtDL6Iq4khyJ/uZBlhrNeW8mU3OcxLXcFAMhH4Mg1wM</span></span></td>
      </tr>
      <tr>
        <td id="L100" class="blob-num js-line-number" data-line-number="100"></td>
        <td id="LC100" class="blob-code js-file-line"><span class="pl-s2"><span class="pl-s1">sKMS2ujqGDfzJOSK7Ig5nwzmh+CcImfqqkDAYqw3RUWOH/03U68VpgNWCYUBhJU2SxP436KYMLiQ</span></span></td>
      </tr>
      <tr>
        <td id="L101" class="blob-num js-line-number" data-line-number="101"></td>
        <td id="LC101" class="blob-code js-file-line"><span class="pl-s2"><span class="pl-s1">9b6QrEsgFvG8NdA+T0kupEjYfQz6yG8T2mEG682xG55FHzXw0gFr0CGF5UGF2/MMsgT2Z+o37p+P</span></span></td>
      </tr>
      <tr>
        <td id="L102" class="blob-num js-line-number" data-line-number="102"></td>
        <td id="LC102" class="blob-code js-file-line"><span class="pl-s2"><span class="pl-s1">KRO7A+uZOhFyHnRO+djrNcDapoljLWe9yaAq+Rg6Trxeo1uHaTFT53bNYb0rCCzWlPNJNvm9ujUB</span></span></td>
      </tr>
      <tr>
        <td id="L103" class="blob-num js-line-number" data-line-number="103"></td>
        <td id="LC103" class="blob-code js-file-line"><span class="pl-s2"><span class="pl-s1">gYVfb5JdxJs0saA4YOWQZbmdxS9DhFMgzpTurfy6jZi0+KCFWxw9uNqojI8ntAV2C49nwygAr6sA</span></span></td>
      </tr>
      <tr>
        <td id="L104" class="blob-num js-line-number" data-line-number="104"></td>
        <td id="LC104" class="blob-code js-file-line"><span class="pl-s2"><span class="pl-s1">SBhYY7ADdHEIL/0aBYhUT7LHmHopEJWcznpXQxCD+yPQkXNgweEZHzFjd6gGWNG09QL0VD0XWXW6</span></span></td>
      </tr>
      <tr>
        <td id="L105" class="blob-num js-line-number" data-line-number="105"></td>
        <td id="LC105" class="blob-code js-file-line"><span class="pl-s2"><span class="pl-s1">dw4LyEoUbvC1McceznpXWrgoxlWOrCzVYoRFNnk2T+iVAYG1AFnKslVzogOwGOtNEXmM9c66XYLa</span></span></td>
      </tr>
      <tr>
        <td id="L106" class="blob-num js-line-number" data-line-number="106"></td>
        <td id="LC106" class="blob-code js-file-line"><span class="pl-s2"><span class="pl-s1">XZQhL1J4N+tNIlY9cD6K2kfcv7NiHhjPQXuq5Ep0TZuY/RsMJBmXU1jvqzGqGa/Pst4lPj6gKfen</span></span></td>
      </tr>
      <tr>
        <td id="L107" class="blob-num js-line-number" data-line-number="107"></td>
        <td id="LC107" class="blob-code js-file-line"><span class="pl-s2"><span class="pl-s1">TL2ulEiR+D3rXYjvNcXTc6XCXY0G4kpwz1ax3sXlxOB6vaZu9yog+CTA+xywJm5gva/6dBBQD0QD</span></span></td>
      </tr>
      <tr>
        <td id="L108" class="blob-num js-line-number" data-line-number="108"></td>
        <td id="LC108" class="blob-code js-file-line"><span class="pl-s2"><span class="pl-s1">NrIyxJT8h8DaaCjaVwykfQICazbrfbk6SiXYxxFYf6mAtZj5uwWgvIJNXmdL/B6vyzWf9S77IsB0</span></span></td>
      </tr>
      <tr>
        <td id="L109" class="blob-num js-line-number" data-line-number="109"></td>
        <td id="LC109" class="blob-code js-file-line"><span class="pl-s2"><span class="pl-s1">B5Tx93D/8DGPSy7ofLZnzTX5PtwJ/eU0cEvvUjwU7qXhPXyyD4pXiM5wgeK4k5GJjwcvljkKMOiS</span></span></td>
      </tr>
      <tr>
        <td id="L110" class="blob-num js-line-number" data-line-number="110"></td>
        <td id="LC110" class="blob-code js-file-line"><span class="pl-s2"><span class="pl-s1">/aoK10wG1jJFh4xbITQp/6agCKab5GF9RlHW2QpoJWVlnxAw6B7J1xTn+nfN8SYL+H1M4WJFuXX4</span></span></td>
      </tr>
      <tr>
        <td id="L111" class="blob-num js-line-number" data-line-number="111"></td>
        <td id="LC111" class="blob-code js-file-line"><span class="pl-s2"><span class="pl-s1">M9W7gnuB1bPTon1eUFhFCy3LiMCYp+E9nPIQeiqrBvy+yO1Ya1j29Sw5KznOfVspuVri7ft/Vjz1</span></span></td>
      </tr>
      <tr>
        <td id="L112" class="blob-num js-line-number" data-line-number="112"></td>
        <td id="LC112" class="blob-code js-file-line"><span class="pl-s2"><span class="pl-s1">8fuAJxjWa36MGxX3utB1msDzBcwsa35DjEuEgXWxwz18L+vdZ/B8zfH7sd4JFJXcyPTLUgvr9p6E</span></span></td>
      </tr>
      <tr>
        <td id="L113" class="blob-num js-line-number" data-line-number="113"></td>
        <td id="LC113" class="blob-code js-file-line"><span class="pl-s2"><span class="pl-s1">h8QXEu6bbFnFxYYvYuYrlz7F1OtukQyJCBiI4PsvwdJhmoEppq1/yPasV2QiYpaqAgNTTJGLd+C+</span></span></td>
      </tr>
      <tr>
        <td id="L114" class="blob-num js-line-number" data-line-number="114"></td>
        <td id="LC114" class="blob-code js-file-line"><span class="pl-s2"><span class="pl-s1">Y1CGeL2iyXYvFyJbR6sArC/BIBUzQ3XmtoPK5eDqbGeTZyt3wFNaBH+PMShHDNifQxmvIijvhDaL</span></span></td>
      </tr>
      <tr>
        <td id="L115" class="blob-num js-line-number" data-line-number="115"></td>
        <td id="LC115" class="blob-code js-file-line"><span class="pl-s2"><span class="pl-s1">i6ecA4NxA0PrOFmIsJi+C23xBLhMSe/UidQVsQGFSCC+IuaYvWHwi+TTdaDRDKvIx7vaME4kZnEf</span></span></td>
      </tr>
      <tr>
        <td id="L116" class="blob-num js-line-number" data-line-number="116"></td>
        <td id="LC116" class="blob-code js-file-line"><span class="pl-s2"><span class="pl-s1">hrbZhSy7DliIcwzaOLrGLQqLStxH8V7rm2hIk2RNhLXzRhZmG60Z0MmLXP8WBugRHuWJcspsd3qA</span></span></td>
      </tr>
      <tr>
        <td id="L117" class="blob-num js-line-number" data-line-number="117"></td>
        <td id="LC117" class="blob-code js-file-line"><span class="pl-s2"><span class="pl-s1">yWzyCFzLkZ7nTVNEHQ+BazvZ8Lri7ts7wZV+D9vzKpFtGW+BuogyFjAKrpOQkJCQkJCQkJCQkJCE</span></span></td>
      </tr>
      <tr>
        <td id="L118" class="blob-num js-line-number" data-line-number="118"></td>
        <td id="LC118" class="blob-code js-file-line"><span class="pl-s2"><span class="pl-s1">l/8Df+8XDp+g0JUAAAAASUVORK5CYII=</span></span></td>
      </tr>
      <tr>
        <td id="L119" class="blob-num js-line-number" data-line-number="119"></td>
        <td id="LC119" class="blob-code js-file-line"><span class="pl-s2"><span class="pl-s1"><span class="pl-k">IMAGE</span></span>;</span></td>
      </tr>
      <tr>
        <td id="L120" class="blob-num js-line-number" data-line-number="120"></td>
        <td id="LC120" class="blob-code js-file-line"><span class="pl-s2"></span></td>
      </tr>
      <tr>
        <td id="L121" class="blob-num js-line-number" data-line-number="121"></td>
        <td id="LC121" class="blob-code js-file-line"><span class="pl-s2"></span></td>
      </tr>
      <tr>
        <td id="L122" class="blob-num js-line-number" data-line-number="122"></td>
        <td id="LC122" class="blob-code js-file-line"><span class="pl-s2">	<span class="pl-s3">header</span>(<span class="pl-s1"><span class="pl-pds">&#39;</span>Content-type: image/png<span class="pl-pds">&#39;</span></span>);</span></td>
      </tr>
      <tr>
        <td id="L123" class="blob-num js-line-number" data-line-number="123"></td>
        <td id="LC123" class="blob-code js-file-line"><span class="pl-s2">	<span class="pl-s3">echo</span> <span class="pl-s3">base64_decode</span>(<span class="pl-vo">$data</span>);</span></td>
      </tr>
      <tr>
        <td id="L124" class="blob-num js-line-number" data-line-number="124"></td>
        <td id="LC124" class="blob-code js-file-line"><span class="pl-s2">	<span class="pl-k">exit</span>;</span></td>
      </tr>
      <tr>
        <td id="L125" class="blob-num js-line-number" data-line-number="125"></td>
        <td id="LC125" class="blob-code js-file-line"><span class="pl-s2">}</span></td>
      </tr>
      <tr>
        <td id="L126" class="blob-num js-line-number" data-line-number="126"></td>
        <td id="LC126" class="blob-code js-file-line"><span class="pl-s2"><span class="pl-k">elseif</span> (<span class="pl-s3">isset</span>(<span class="pl-vo">$_GET</span>[<span class="pl-s1"><span class="pl-pds">&#39;</span>background<span class="pl-pds">&#39;</span></span>]))</span></td>
      </tr>
      <tr>
        <td id="L127" class="blob-num js-line-number" data-line-number="127"></td>
        <td id="LC127" class="blob-code js-file-line"><span class="pl-s2">{</span></td>
      </tr>
      <tr>
        <td id="L128" class="blob-num js-line-number" data-line-number="128"></td>
        <td id="LC128" class="blob-code js-file-line"><span class="pl-s2">	<span class="pl-vo">$data</span> <span class="pl-k">=</span> <span class="pl-s1"><span class="pl-pds">&lt;&lt;&lt;</span><span class="pl-k">IMAGE</span></span></span></td>
      </tr>
      <tr>
        <td id="L129" class="blob-num js-line-number" data-line-number="129"></td>
        <td id="LC129" class="blob-code js-file-line"><span class="pl-s2"><span class="pl-s1">R0lGODlhMAEeAeYAAP///8ni6cTf5+72+PD3+c3k6+nz9ufy9ev099Pn7bnZ48bg6LfY4uHv8/r8</span></span></td>
      </tr>
      <tr>
        <td id="L130" class="blob-num js-line-number" data-line-number="130"></td>
        <td id="LC130" class="blob-code js-file-line"><span class="pl-s2"><span class="pl-s1">/f3+/v7//7bX4cjh6fj7/Mvj6vz9/rva4+z19/X6+/f7/Pn8/fb6+7jZ4vv9/bra473b5Lzb5LXX</span></span></td>
      </tr>
      <tr>
        <td id="L131" class="blob-num js-line-number" data-line-number="131"></td>
        <td id="LC131" class="blob-code js-file-line"><span class="pl-s2"><span class="pl-s1">4b7c5b/c5e31+NTo7tvs8dfq7/H3+bjY4tnq79Hm7c/l69nr8PL4+t7t8sDd5cLe5uPw9Nvr8Mri</span></span></td>
      </tr>
      <tr>
        <td id="L132" class="blob-num js-line-number" data-line-number="132"></td>
        <td id="LC132" class="blob-code js-file-line"><span class="pl-s2"><span class="pl-s1">6fP4+tLn7er099Hm7O/2+dDm7OTw9OXx9Nbp7tbp7+Lv8+Du8szj6sXg57bY4d3s8djq7+jz9tzs</span></span></td>
      </tr>
      <tr>
        <td id="L133" class="blob-num js-line-number" data-line-number="133"></td>
        <td id="LC133" class="blob-code js-file-line"><span class="pl-s2"><span class="pl-s1">8cPe58fh6M7k68Pf5+by9fz+/sDd5vT5+vT5+97t8bbY4trr8P7+/v7+/+Pw8+Xx9dXo7sHe5vH4</span></span></td>
      </tr>
      <tr>
        <td id="L134" class="blob-num js-line-number" data-line-number="134"></td>
        <td id="LC134" class="blob-code js-file-line"><span class="pl-s2"><span class="pl-s1">+fP5+sHd5t/u8s/l7AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA</span></span></td>
      </tr>
      <tr>
        <td id="L135" class="blob-num js-line-number" data-line-number="135"></td>
        <td id="LC135" class="blob-code js-file-line"><span class="pl-s2"><span class="pl-s1">AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAACH5</span></span></td>
      </tr>
      <tr>
        <td id="L136" class="blob-num js-line-number" data-line-number="136"></td>
        <td id="LC136" class="blob-code js-file-line"><span class="pl-s2"><span class="pl-s1">BAAAAAAALAAAAAAwAR4BAAf/gCGCg4SFhoeIiYqLjI2Oj5CRkpOUlZaXmJmam5ydnp+goaKNEaWm</span></span></td>
      </tr>
      <tr>
        <td id="L137" class="blob-num js-line-number" data-line-number="137"></td>
        <td id="LC137" class="blob-code js-file-line"><span class="pl-s2"><span class="pl-s1">p6ipqqusra6vsLGys7S1tre4ubq7vL2+v8DBwsPExbBDyMnKy8zNzs/Q0dLT1NXW19jZ2tvc3d7K</span></span></td>
      </tr>
      <tr>
        <td id="L138" class="blob-num js-line-number" data-line-number="138"></td>
        <td id="LC138" class="blob-code js-file-line"><span class="pl-s2"><span class="pl-s1">UuHi4+Tl5ufo6err7O3u7/Dx8vP09fb34wz6+/z9/v8AAwocSLCgwYMIEypcyLChw4cQI0qcSLGi</span></span></td>
      </tr>
      <tr>
        <td id="L139" class="blob-num js-line-number" data-line-number="139"></td>
        <td id="LC139" class="blob-code js-file-line"><span class="pl-s2"><span class="pl-s1">xYsYM2osmKKjx48gQ4ocSbKkyZMoU6pcybKly5cwY8qc+ZGDzZs4c+rcybOnz59AgwodSrSo0aNI</span></span></td>
      </tr>
      <tr>
        <td id="L140" class="blob-num js-line-number" data-line-number="140"></td>
        <td id="LC140" class="blob-code js-file-line"><span class="pl-s2"><span class="pl-s1">kypdyrSp06dQo0qdSrWq1aAKsmrdyrWr169gw4odS7as2bNo06pdy7at27dw/+PKnUu3rt27ePOS</span></span></td>
      </tr>
      <tr>
        <td id="L141" class="blob-num js-line-number" data-line-number="141"></td>
        <td id="LC141" class="blob-code js-file-line"><span class="pl-s2"><span class="pl-s1">9cC3r9+/gAMLHky4sOHDiBMrXsy4sePHkCNLnky5suXLmDNr3sz5sIXPoEOLHk26tOnTqFOrXs26</span></span></td>
      </tr>
      <tr>
        <td id="L142" class="blob-num js-line-number" data-line-number="142"></td>
        <td id="LC142" class="blob-code js-file-line"><span class="pl-s2"><span class="pl-s1">tevXsGPLnk27tu3buHPr3s27t+/fqkEIH068uPHjyJMrX868ufPn0KNLn069uvXr2LNr3869u/fv</span></span></td>
      </tr>
      <tr>
        <td id="L143" class="blob-num js-line-number" data-line-number="143"></td>
        <td id="LC143" class="blob-code js-file-line"><span class="pl-s2"><span class="pl-s1">4MOLb/6hvPnz6NOrX8++vfv38OPLn0+/vv37+PPr38+/v///AAYo4IAEFgifCAgmqOCCDDbo4IMQ</span></span></td>
      </tr>
      <tr>
        <td id="L144" class="blob-num js-line-number" data-line-number="144"></td>
        <td id="LC144" class="blob-code js-file-line"><span class="pl-s2"><span class="pl-s1">RijhhBRWaOGFGGao4YYcdujhhyCGKOKIJJZo4okoTjjCiiy26OKLMMYo44w01mjjjTjmqOOOPPbo</span></span></td>
      </tr>
      <tr>
        <td id="L145" class="blob-num js-line-number" data-line-number="145"></td>
        <td id="LC145" class="blob-code js-file-line"><span class="pl-s2"><span class="pl-s1">449ABinkkEQWaeSRSCap5P+SNsLg5JNQRinllFRWaeWVWGap5ZZcdunll2CGKeaYZELpxJlopqnm</span></span></td>
      </tr>
      <tr>
        <td id="L146" class="blob-num js-line-number" data-line-number="146"></td>
        <td id="LC146" class="blob-code js-file-line"><span class="pl-s2"><span class="pl-s1">mmy26eabcMYp55x01mnnnXjmqeeefPaZJheABirooIQWauihiCaq6KKMNuroo5BGKumklFZqqaBZ</span></span></td>
      </tr>
      <tr>
        <td id="L147" class="blob-num js-line-number" data-line-number="147"></td>
        <td id="LC147" class="blob-code js-file-line"><span class="pl-s2"><span class="pl-s1">ZKrpppx26umnoIYq6qiklmrqqaimquqqrLbq6qubxiDrrLTWauutuOaq66689urrr8AGK+ywxBZr</span></span></td>
      </tr>
      <tr>
        <td id="L148" class="blob-num js-line-number" data-line-number="148"></td>
        <td id="LC148" class="blob-code js-file-line"><span class="pl-s2"><span class="pl-s1">7LHI0orEssw26+yz0EYr7bTUVmvttdhmq+223Hbr7bfghtvsEuSWa+656Kar7rrstuvuu/DGK++8</span></span></td>
      </tr>
      <tr>
        <td id="L149" class="blob-num js-line-number" data-line-number="149"></td>
        <td id="LC149" class="blob-code js-file-line"><span class="pl-s2"><span class="pl-s1">9NZr77345quvuQL06++/AAcs8MAEF2zwwQgnrPDCDDfs8MMQRyzxxBRXbPH/xRhnrPHGHHeMsBAg</span></span></td>
      </tr>
      <tr>
        <td id="L150" class="blob-num js-line-number" data-line-number="150"></td>
        <td id="LC150" class="blob-code js-file-line"><span class="pl-s2"><span class="pl-s1">hyzyyCSXbPLJKKes8sost+zyyzDHLPPMNNdss8gL5Kzzzjz37PPPQAct9NBEF2300UgnrfTSTDft</span></span></td>
      </tr>
      <tr>
        <td id="L151" class="blob-num js-line-number" data-line-number="151"></td>
        <td id="LC151" class="blob-code js-file-line"><span class="pl-s2"><span class="pl-s1">9NNQRy311FRXbfXVWGdNdBJcd+3112CHLfbYZJdt9tlop6322my37fbbcMctt9cS1G333Xjnrffe</span></span></td>
      </tr>
      <tr>
        <td id="L152" class="blob-num js-line-number" data-line-number="152"></td>
        <td id="LC152" class="blob-code js-file-line"><span class="pl-s2"><span class="pl-s1">fPft99+ABy744IQXbvjhiCeu+OKMN+7445BHLvnklFcOeACYZ6755px37vnnoIcu+uikl2766ain</span></span></td>
      </tr>
      <tr>
        <td id="L153" class="blob-num js-line-number" data-line-number="153"></td>
        <td id="LC153" class="blob-code js-file-line"><span class="pl-s2"><span class="pl-s1">rvrqrLfu+uuwxy777LTXbvvtuI9Ow+689+7778AHL/zwxBdv/PHIJ6/88sw37/zz0EffOwXUV2/9</span></span></td>
      </tr>
      <tr>
        <td id="L154" class="blob-num js-line-number" data-line-number="154"></td>
        <td id="LC154" class="blob-code js-file-line"><span class="pl-s2"><span class="pl-s1">9dhnr/323Hfv/ffghy/+//jkl2/++einr/767Lfv/vvwxy///PR/H8T9+Oev//789+///wAMoAAH</span></span></td>
      </tr>
      <tr>
        <td id="L155" class="blob-num js-line-number" data-line-number="155"></td>
        <td id="LC155" class="blob-code js-file-line"><span class="pl-s2"><span class="pl-s1">SMACGvCACEygAhfIwAbmrwAQjKAEJ0jBClrwghjMoAY3yMEOevCDIAyhCEdIwhKa8IQoTKEKV8jC</span></span></td>
      </tr>
      <tr>
        <td id="L156" class="blob-num js-line-number" data-line-number="156"></td>
        <td id="LC156" class="blob-code js-file-line"><span class="pl-s2"><span class="pl-s1">FrrwhTDcoBJmSMMa2vCGOMyhDnfIwx768IdADKIQh0jEIhrxiEhMYg1ZwMQmOvGJUIyiFKdIxSpa</span></span></td>
      </tr>
      <tr>
        <td id="L157" class="blob-num js-line-number" data-line-number="157"></td>
        <td id="LC157" class="blob-code js-file-line"><span class="pl-s2"><span class="pl-s1">8YpYzKIWt8jFLnrxi2AMoxid6IUymvGMaEyjGtfIxja68Y1wjKMc50jHOtrxjnjMox73eEYd+PGP</span></span></td>
      </tr>
      <tr>
        <td id="L158" class="blob-num js-line-number" data-line-number="158"></td>
        <td id="LC158" class="blob-code js-file-line"><span class="pl-s2"><span class="pl-s1">gAykIAdJyEIa8pCITKQiF8nIRjrykZCMpCQnSUlA4uCSmMykJjfJyU568v+ToAylKEdJylKa8pSo</span></span></td>
      </tr>
      <tr>
        <td id="L159" class="blob-num js-line-number" data-line-number="159"></td>
        <td id="LC159" class="blob-code js-file-line"><span class="pl-s2"><span class="pl-s1">TKUqV8nKVmZyBbCMpSxnScta2vKWuMylLnfJy1768pfADKYwh0nMYhpTljZIpjKXycxmOvOZ0Iym</span></span></td>
      </tr>
      <tr>
        <td id="L160" class="blob-num js-line-number" data-line-number="160"></td>
        <td id="LC160" class="blob-code js-file-line"><span class="pl-s2"><span class="pl-s1">NKdJzWpa85rYzKY2t8nNbnrzm8tMgDjHSc5ymvOc6EynOtfJzna6853wjKc850nPetrznvjMpz73</span></span></td>
      </tr>
      <tr>
        <td id="L161" class="blob-num js-line-number" data-line-number="161"></td>
        <td id="LC161" class="blob-code js-file-line"><span class="pl-s2"><span class="pl-s1">yc9++vOfAA2oQNtZgoIa9KAITahCF8rQhjr0oRCNqEQnStGKWvSiGM2oRjd6UCx49KMgDalIR0rS</span></span></td>
      </tr>
      <tr>
        <td id="L162" class="blob-num js-line-number" data-line-number="162"></td>
        <td id="LC162" class="blob-code js-file-line"><span class="pl-s2"><span class="pl-s1">kpr0pChNqUpXytKWuvSlMI2pTGdKU5D24KY4zalOd8rTnvr0p0ANqlCHStSiGvWoSE2qUpfK1Kbm</span></span></td>
      </tr>
      <tr>
        <td id="L163" class="blob-num js-line-number" data-line-number="163"></td>
        <td id="LC163" class="blob-code js-file-line"><span class="pl-s2"><span class="pl-s1">1AdQjapUp0rVqlr1qlj/zapWt8rVrnr1q2ANq1jHStaymlWqJ0irWtfK1ra69a1wjatc50rXutr1</span></span></td>
      </tr>
      <tr>
        <td id="L164" class="blob-num js-line-number" data-line-number="164"></td>
        <td id="LC164" class="blob-code js-file-line"><span class="pl-s2"><span class="pl-s1">rnjNq173yte++vWvay2CYAdL2MIa9rCITaxiF8vYxjr2sZCNrGQnS9nKWvaymCWsCjbL2c569rOg</span></span></td>
      </tr>
      <tr>
        <td id="L165" class="blob-num js-line-number" data-line-number="165"></td>
        <td id="LC165" class="blob-code js-file-line"><span class="pl-s2"><span class="pl-s1">Da1oR0va0pr2tKhNrWpXy9rWuva1sI1tZ1tA29ra9ra4za1ud8vb3vr2t8ANrnCHS9ziGve4yE2u</span></span></td>
      </tr>
      <tr>
        <td id="L166" class="blob-num js-line-number" data-line-number="166"></td>
        <td id="LC166" class="blob-code js-file-line"><span class="pl-s2"><span class="pl-s1">cm07heY697nQja50p0vd6lr3utjNrna3y93ueve74A2veMf73BmY97zoTa9618ve9rr3vfCNr3zn</span></span></td>
      </tr>
      <tr>
        <td id="L167" class="blob-num js-line-number" data-line-number="167"></td>
        <td id="LC167" class="blob-code js-file-line"><span class="pl-s2"><span class="pl-s1">S9/62ve++M2vfvfLX/Sa4L8ADrCAB0zgAhv4wAhOsIIXzOAGO/jBEI6w/4QnTOEKB/gIGM6whjfM</span></span></td>
      </tr>
      <tr>
        <td id="L168" class="blob-num js-line-number" data-line-number="168"></td>
        <td id="LC168" class="blob-code js-file-line"><span class="pl-s2"><span class="pl-s1">4Q57+MMgDrGIR0ziEpv4xChOsYpXzOIWu1jDRIixjGdM4xrb+MY4zrGOd8zjHvv4x0AOspCHTOQi</span></span></td>
      </tr>
      <tr>
        <td id="L169" class="blob-num js-line-number" data-line-number="169"></td>
        <td id="LC169" class="blob-code js-file-line"><span class="pl-s2"><span class="pl-s1">G/nIM46CkpfM5CY7+clQjrKUp0zlKlv5yljOspa3zOUue/nLYGbyC8ZM5jKb+cxoTrOa18zmNrv5</span></span></td>
      </tr>
      <tr>
        <td id="L170" class="blob-num js-line-number" data-line-number="170"></td>
        <td id="LC170" class="blob-code js-file-line"><span class="pl-s2"><span class="pl-s1">zXCOs5znTOc62/nOeM5zmbvA5z77+c+ADrSgB03oQhv60IhOtKIXzehGO/rRkI60pP0MhEpb+tKY</span></span></td>
      </tr>
      <tr>
        <td id="L171" class="blob-num js-line-number" data-line-number="171"></td>
        <td id="LC171" class="blob-code js-file-line"><span class="pl-s2"><span class="pl-s1">zrSmN83pTnv606AOtahHTepSm/rUqE61qld96Qa4+tWwjrWsZ03rWtv61rjOta53zete+/rXwA62</span></span></td>
      </tr>
      <tr>
        <td id="L172" class="blob-num js-line-number" data-line-number="172"></td>
        <td id="LC172" class="blob-code js-file-line"><span class="pl-s2"><span class="pl-s1">sIdN7GIb+9jITrayl//N7GY7O9c/iLa0p03talv72tjOtra3ze1ue/vb4A63uMdN7nKb+9zTtoK6</span></span></td>
      </tr>
      <tr>
        <td id="L173" class="blob-num js-line-number" data-line-number="173"></td>
        <td id="LC173" class="blob-code js-file-line"><span class="pl-s2"><span class="pl-s1">183udrv73fCOt7znTe962/ve+M63vvfN7377+98AZ7cMBk7wghv84AhPuMIXzvCGO/zhEI+4xCdO</span></span></td>
      </tr>
      <tr>
        <td id="L174" class="blob-num js-line-number" data-line-number="174"></td>
        <td id="LC174" class="blob-code js-file-line"><span class="pl-s2"><span class="pl-s1">8Ypb/OIYz3jBd8Dxjnv84yAPuchHTvKSm/zkKE+5ylfO8pa7/OUwj7nMPc6Dmtv85jjPuc53zvOe</span></span></td>
      </tr>
      <tr>
        <td id="L175" class="blob-num js-line-number" data-line-number="175"></td>
        <td id="LC175" class="blob-code js-file-line"><span class="pl-s2"><span class="pl-s1">+/znQA+60IdO9KIb/ehIT7rSl37zKzj96VCPutSnTvWqW/3qWM+61rfO9a57/etgD7vYx052qDPh</span></span></td>
      </tr>
      <tr>
        <td id="L176" class="blob-num js-line-number" data-line-number="176"></td>
        <td id="LC176" class="blob-code js-file-line"><span class="pl-s2"><span class="pl-s1">7GhPu9rXzva2u/3tcI+73OdO97rb/e54z7ve9873vqf9AIAPvOAHT/j/whv+8IhPvOIXz/jGO/7x</span></span></td>
      </tr>
      <tr>
        <td id="L177" class="blob-num js-line-number" data-line-number="177"></td>
        <td id="LC177" class="blob-code js-file-line"><span class="pl-s2"><span class="pl-s1">kI+85CdP+cpb/vKYz7zmN8/5znv+86BfvBFGT/rSm/70qE+96lfP+ta7/vWwj73sZ0/72tv+9rjP</span></span></td>
      </tr>
      <tr>
        <td id="L178" class="blob-num js-line-number" data-line-number="178"></td>
        <td id="LC178" class="blob-code js-file-line"><span class="pl-s2"><span class="pl-s1">fekNwPve+/73wA++8IdP/OIb//jIT77yl8/85jv/+dCPvvSnT/3qW//62M++9rfP/ePf4PvgD7/4</span></span></td>
      </tr>
      <tr>
        <td id="L179" class="blob-num js-line-number" data-line-number="179"></td>
        <td id="LC179" class="blob-code js-file-line"><span class="pl-s2"><span class="pl-s1">x0/+8pv//OhPv/rXz/72u//98I+//OdP//qHHwH4z7/+98///vv//wAYgAI4gARYgAZ4gAiYgAq4</span></span></td>
      </tr>
      <tr>
        <td id="L180" class="blob-num js-line-number" data-line-number="180"></td>
        <td id="LC180" class="blob-code js-file-line"><span class="pl-s2"><span class="pl-s1">gAzYgA74gBAYgRI4gRRYgRZ4gRg4gBewgRzYgR74gSAYgiI4giRYgiZ4giiYgiq4gizYgi74gjAY</span></span></td>
      </tr>
      <tr>
        <td id="L181" class="blob-num js-line-number" data-line-number="181"></td>
        <td id="LC181" class="blob-code js-file-line"><span class="pl-s2"><span class="pl-s1">gzI4gzRYgzZ4gziY/4M6uIMmSAI++INAGIRCOIREWIRGeIRImIRKuIRM2IRO+IRQGIVSOIVUCIQD</span></span></td>
      </tr>
      <tr>
        <td id="L182" class="blob-num js-line-number" data-line-number="182"></td>
        <td id="LC182" class="blob-code js-file-line"><span class="pl-s2"><span class="pl-s1">cIVYmIVauIVc2IVe+IVgGIZiOIZkWIZmeIZomIZquIZs2IZu+IZwGIdyOId0WId2eIdimAN6uId8</span></span></td>
      </tr>
      <tr>
        <td id="L183" class="blob-num js-line-number" data-line-number="183"></td>
        <td id="LC183" class="blob-code js-file-line"><span class="pl-s2"><span class="pl-s1">2Id++IeAGIiCOIiEWIiGeIiImIiKuIiM2IiO+IiQyIcEMImUWImWeImYmImauImc2Ime+ImgGIqi</span></span></td>
      </tr>
      <tr>
        <td id="L184" class="blob-num js-line-number" data-line-number="184"></td>
        <td id="LC184" class="blob-code js-file-line"><span class="pl-s2"><span class="pl-s1">OIqkWIqmeIqomIqquIqs2Iqu+IqwGIuyOIueiAK2eIu4mIu6uIu82Iu++IvAGIzCOIzEWIzGeIzI</span></span></td>
      </tr>
      <tr>
        <td id="L185" class="blob-num js-line-number" data-line-number="185"></td>
        <td id="LC185" class="blob-code js-file-line"><span class="pl-s2"><span class="pl-s1">mIzKuIzMiIta8IzQGI3SOI3UWI3WeI3YmI3auI3c2I3e+I3gGI7iOP+O5FiO0egC6JiO6riO7NiO</span></span></td>
      </tr>
      <tr>
        <td id="L186" class="blob-num js-line-number" data-line-number="186"></td>
        <td id="LC186" class="blob-code js-file-line"><span class="pl-s2"><span class="pl-s1">7viO8BiP8jiP9FiP9niP+JiP+riP/NiP/qiONRCQAjmQBFmQBnmQCJmQCrmQDNmQDvmQEBmREjmR</span></span></td>
      </tr>
      <tr>
        <td id="L187" class="blob-num js-line-number" data-line-number="187"></td>
        <td id="LC187" class="blob-code js-file-line"><span class="pl-s2"><span class="pl-s1">FFmRFnmRA7kFGrmRHNmRHvmRIBmSIjmSJFmSJnmSKJmSKrmSLNmSLvmSMMmRTzCTNFmTNnmTOJmT</span></span></td>
      </tr>
      <tr>
        <td id="L188" class="blob-num js-line-number" data-line-number="188"></td>
        <td id="LC188" class="blob-code js-file-line"><span class="pl-s2"><span class="pl-s1">OrmTPNmTPvmTQBmUQjmURFmURnmUSJmUNQkFTNmUTvmUUBmVUjmVVFmVVnmVWJmVWrmVXNmVXvmV</span></span></td>
      </tr>
      <tr>
        <td id="L189" class="blob-num js-line-number" data-line-number="189"></td>
        <td id="LC189" class="blob-code js-file-line"><span class="pl-s2"><span class="pl-s1">YBmWYumUGFCWZnmWaJmWarmWbNmWbvmWcBmXcjmXdFmXdnmXeJmXermXfNmXfvmXgBmYgjmYhFmY</span></span></td>
      </tr>
      <tr>
        <td id="L190" class="blob-num js-line-number" data-line-number="190"></td>
        <td id="LC190" class="blob-code js-file-line"><span class="pl-s2"><span class="pl-s1">cLkBiJmYirmYjNn/mI75mJAZmZI5mZRZmZZ5mZiZmZq5mZzZmZ75maAZmqI5mqRZmqZ5mqg5mRmw</span></span></td>
      </tr>
      <tr>
        <td id="L191" class="blob-num js-line-number" data-line-number="191"></td>
        <td id="LC191" class="blob-code js-file-line"><span class="pl-s2"><span class="pl-s1">mqzZmq75mrAZm7I5m7RZm7Z5m7iZm7q5m7zZm775m8AZnMI5nMRZnMZ5nMiZnMq5nLY5Ac75nNAZ</span></span></td>
      </tr>
      <tr>
        <td id="L192" class="blob-num js-line-number" data-line-number="192"></td>
        <td id="LC192" class="blob-code js-file-line"><span class="pl-s2"><span class="pl-s1">ndI5ndRZndZ5ndiZndq5ndzZnd75neAZnuI5nuRZnuZ5nuiZnuq5nuzZnu6ZnRoQn/I5n/RZn/Z5</span></span></td>
      </tr>
      <tr>
        <td id="L193" class="blob-num js-line-number" data-line-number="193"></td>
        <td id="LC193" class="blob-code js-file-line"><span class="pl-s2"><span class="pl-s1">n/iZn/q5n/zZn/75nwAaoAI6oARaoAZ6oAiaoAq6oAzaoA76oBAaofzpABRaoRZ6oRiaoRq6oRza</span></span></td>
      </tr>
      <tr>
        <td id="L194" class="blob-num js-line-number" data-line-number="194"></td>
        <td id="LC194" class="blob-code js-file-line"><span class="pl-s2"><span class="pl-s1">oR76oSAaoiI6oiRaoiZ6oiiaoiq6oizaoi76ojAaozI6ozT6oR1w/6M4mqM6uqM82qM++qNAGqRC</span></span></td>
      </tr>
      <tr>
        <td id="L195" class="blob-num js-line-number" data-line-number="195"></td>
        <td id="LC195" class="blob-code js-file-line"><span class="pl-s2"><span class="pl-s1">OqREWqRGeqRImqRKuqRM2qRO+qRQGqVSOqVUWqVWeqVCWgFauqVc2qVe+qVgGqZiOqZkWqZmeqZo</span></span></td>
      </tr>
      <tr>
        <td id="L196" class="blob-num js-line-number" data-line-number="196"></td>
        <td id="LC196" class="blob-code js-file-line"><span class="pl-s2"><span class="pl-s1">mqZquqZs2qZu+qZwGqdyOqd0Wqd2eqd4mqd6WqZN0Kd++qeAGqiCOqiEWqiGeqiImqiKuqiM2qiO</span></span></td>
      </tr>
      <tr>
        <td id="L197" class="blob-num js-line-number" data-line-number="197"></td>
        <td id="LC197" class="blob-code js-file-line"><span class="pl-s2"><span class="pl-s1">+qiQGqmSOql/+gCWeqmYmqmauqmc2qme+qmgGqqiOqqkWqqmeqqomqqquqqs2qqu+qqwGquyOqu0</span></span></td>
      </tr>
      <tr>
        <td id="L198" class="blob-num js-line-number" data-line-number="198"></td>
        <td id="LC198" class="blob-code js-file-line"><span class="pl-s2"><span class="pl-s1">Wqu2GqpUkKu6uqu82qu++qvAGqzCOqzEWqzGeqzImqzKuqzM2qzO+qy7WgXSOq3UWq3Weq3Ymq3a</span></span></td>
      </tr>
      <tr>
        <td id="L199" class="blob-num js-line-number" data-line-number="199"></td>
        <td id="LC199" class="blob-code js-file-line"><span class="pl-s2"><span class="pl-s1">uq3c2q3e+q3gGv+u4jqu5Fqu5nqu6EqtELCu7Nqu7vqu8Bqv8jqv9Fqv9nqv+Jqv+rqv/Nqv/vqv</span></span></td>
      </tr>
      <tr>
        <td id="L200" class="blob-num js-line-number" data-line-number="200"></td>
        <td id="LC200" class="blob-code js-file-line"><span class="pl-s2"><span class="pl-s1">ABuwAjuwBFuwBnuwCJuwCruw9goADvuwEBuxEjuxFFuxFnuxGJuxGruxHNuxHvuxIBuyIjuyJFuy</span></span></td>
      </tr>
      <tr>
        <td id="L201" class="blob-num js-line-number" data-line-number="201"></td>
        <td id="LC201" class="blob-code js-file-line"><span class="pl-s2"><span class="pl-s1">JnuyKJuyKruyLNuyLvuyMBuzMjuzNFuzNnuzOJuzOruzPNuzPvuzQBu0Qju0RFu0Rnu0SJu0Sru0</span></span></td>
      </tr>
      <tr>
        <td id="L202" class="blob-num js-line-number" data-line-number="202"></td>
        <td id="LC202" class="blob-code js-file-line"><span class="pl-s2"><span class="pl-s1">TNu0Tvu0UBu1Uju1VFu1Vnu1WJu1Wru1XNu1Xvu1YBu2Yju2ZFu2Znu2aJu2aru2bNu2bvu2cBu3</span></span></td>
      </tr>
      <tr>
        <td id="L203" class="blob-num js-line-number" data-line-number="203"></td>
        <td id="LC203" class="blob-code js-file-line"><span class="pl-s2"><span class="pl-s1">cju3dFu3dnu3eJu3eru3fNu3fvu3gBu4gju4hFu4hnu4iJu4irv/uIzbuI77uJAbuZI7uZRbuZZ7</span></span></td>
      </tr>
      <tr>
        <td id="L204" class="blob-num js-line-number" data-line-number="204"></td>
        <td id="LC204" class="blob-code js-file-line"><span class="pl-s2"><span class="pl-s1">uZibuZq7uZzbuZ77uaAbuqI7uqRbuqZ7uqibuqq7uqzbuq77urAbu7I7u7Rbu7Z7u7ibu7q7u7zb</span></span></td>
      </tr>
      <tr>
        <td id="L205" class="blob-num js-line-number" data-line-number="205"></td>
        <td id="LC205" class="blob-code js-file-line"><span class="pl-s2"><span class="pl-s1">u777u8AbvMI7vMRbvMZ7vMibvMq7vMzbvM77vNAbvdI7vdRbvdZ7vdibvdq7vdzbvd77veAbvuI7</span></span></td>
      </tr>
      <tr>
        <td id="L206" class="blob-num js-line-number" data-line-number="206"></td>
        <td id="LC206" class="blob-code js-file-line"><span class="pl-s2"><span class="pl-s1">vuRbvuZ7vuibvuq7vuzbvu77vvAbv/I7v/Rbv/Z7v/ibv/q7v/zbv/77vwAcwAI8wARcwAZ8wAic</span></span></td>
      </tr>
      <tr>
        <td id="L207" class="blob-num js-line-number" data-line-number="207"></td>
        <td id="LC207" class="blob-code js-file-line"><span class="pl-s2"><span class="pl-s1">wAq8wAzcwA78wBAcwRI8wRRcwRZ8wRicwRq8wRzcwR78wSAcwiI8wiRcwiZ8wiicwiq8wizcwi78</span></span></td>
      </tr>
      <tr>
        <td id="L208" class="blob-num js-line-number" data-line-number="208"></td>
        <td id="LC208" class="blob-code js-file-line"><span class="pl-s2"><span class="pl-s1">wjAcwzI8wzRcFcM2fMM4nMM6vMM83MM+/MNArLmBAAA7</span></span></td>
      </tr>
      <tr>
        <td id="L209" class="blob-num js-line-number" data-line-number="209"></td>
        <td id="LC209" class="blob-code js-file-line"><span class="pl-s2"><span class="pl-s1"><span class="pl-k">IMAGE</span></span>;</span></td>
      </tr>
      <tr>
        <td id="L210" class="blob-num js-line-number" data-line-number="210"></td>
        <td id="LC210" class="blob-code js-file-line"><span class="pl-s2"></span></td>
      </tr>
      <tr>
        <td id="L211" class="blob-num js-line-number" data-line-number="211"></td>
        <td id="LC211" class="blob-code js-file-line"><span class="pl-s2">	<span class="pl-s3">header</span>(<span class="pl-s1"><span class="pl-pds">&#39;</span>Content-type: image/gif<span class="pl-pds">&#39;</span></span>);</span></td>
      </tr>
      <tr>
        <td id="L212" class="blob-num js-line-number" data-line-number="212"></td>
        <td id="LC212" class="blob-code js-file-line"><span class="pl-s2">	<span class="pl-s3">echo</span> <span class="pl-s3">base64_decode</span>(<span class="pl-vo">$data</span>);</span></td>
      </tr>
      <tr>
        <td id="L213" class="blob-num js-line-number" data-line-number="213"></td>
        <td id="LC213" class="blob-code js-file-line"><span class="pl-s2">	<span class="pl-k">exit</span>;</span></td>
      </tr>
      <tr>
        <td id="L214" class="blob-num js-line-number" data-line-number="214"></td>
        <td id="LC214" class="blob-code js-file-line"><span class="pl-s2">}</span></td>
      </tr>
      <tr>
        <td id="L215" class="blob-num js-line-number" data-line-number="215"></td>
        <td id="LC215" class="blob-code js-file-line"><span class="pl-s2"><span class="pl-k">elseif</span> (<span class="pl-s3">isset</span>(<span class="pl-vo">$_GET</span>[<span class="pl-s1"><span class="pl-pds">&#39;</span>loader<span class="pl-pds">&#39;</span></span>]))</span></td>
      </tr>
      <tr>
        <td id="L216" class="blob-num js-line-number" data-line-number="216"></td>
        <td id="LC216" class="blob-code js-file-line"><span class="pl-s2">{</span></td>
      </tr>
      <tr>
        <td id="L217" class="blob-num js-line-number" data-line-number="217"></td>
        <td id="LC217" class="blob-code js-file-line"><span class="pl-s2">	<span class="pl-vo">$data</span> <span class="pl-k">=</span> <span class="pl-s1"><span class="pl-pds">&lt;&lt;&lt;</span><span class="pl-k">IMAGE</span></span></span></td>
      </tr>
      <tr>
        <td id="L218" class="blob-num js-line-number" data-line-number="218"></td>
        <td id="LC218" class="blob-code js-file-line"><span class="pl-s2"><span class="pl-s1">R0lGODlhEAALAPQAAP///wBmzNro9tDi9Ory+gZpzQBmzC6B1YKz5WCf3rrV8CJ60kqS2oq452Sh</span></span></td>
      </tr>
      <tr>
        <td id="L219" class="blob-num js-line-number" data-line-number="219"></td>
        <td id="LC219" class="blob-code js-file-line"><span class="pl-s2"><span class="pl-s1">377X8SZ80wRozE6U2+bv+djn9vT4/DiH19zp9/L2+7bS76DF68re8+70+gAAAAAAAAAAACH/C05F</span></span></td>
      </tr>
      <tr>
        <td id="L220" class="blob-num js-line-number" data-line-number="220"></td>
        <td id="LC220" class="blob-code js-file-line"><span class="pl-s2"><span class="pl-s1">VFNDQVBFMi4wAwEAAAAh/hpDcmVhdGVkIHdpdGggYWpheGxvYWQuaW5mbwAh+QQJCwAAACwAAAAA</span></span></td>
      </tr>
      <tr>
        <td id="L221" class="blob-num js-line-number" data-line-number="221"></td>
        <td id="LC221" class="blob-code js-file-line"><span class="pl-s2"><span class="pl-s1">EAALAAAFLSAgjmRpnqSgCuLKAq5AEIM4zDVw03ve27ifDgfkEYe04kDIDC5zrtYKRa2WQgAh+QQJ</span></span></td>
      </tr>
      <tr>
        <td id="L222" class="blob-num js-line-number" data-line-number="222"></td>
        <td id="LC222" class="blob-code js-file-line"><span class="pl-s2"><span class="pl-s1">CwAAACwAAAAAEAALAAAFJGBhGAVgnqhpHIeRvsDawqns0qeN5+y967tYLyicBYE7EYkYAgAh+QQJ</span></span></td>
      </tr>
      <tr>
        <td id="L223" class="blob-num js-line-number" data-line-number="223"></td>
        <td id="LC223" class="blob-code js-file-line"><span class="pl-s2"><span class="pl-s1">CwAAACwAAAAAEAALAAAFNiAgjothLOOIJAkiGgxjpGKiKMkbz7SN6zIawJcDwIK9W/HISxGBzdHT</span></span></td>
      </tr>
      <tr>
        <td id="L224" class="blob-num js-line-number" data-line-number="224"></td>
        <td id="LC224" class="blob-code js-file-line"><span class="pl-s2"><span class="pl-s1">uBNOmcJVCyoUlk7CEAAh+QQJCwAAACwAAAAAEAALAAAFNSAgjqQIRRFUAo3jNGIkSdHqPI8Tz3V5</span></span></td>
      </tr>
      <tr>
        <td id="L225" class="blob-num js-line-number" data-line-number="225"></td>
        <td id="LC225" class="blob-code js-file-line"><span class="pl-s2"><span class="pl-s1">5zuaDacDyIQ+YrBH+hWPzJFzOQQaeavWi7oqnVIhACH5BAkLAAAALAAAAAAQAAsAAAUyICCOZGme</span></span></td>
      </tr>
      <tr>
        <td id="L226" class="blob-num js-line-number" data-line-number="226"></td>
        <td id="LC226" class="blob-code js-file-line"><span class="pl-s2"><span class="pl-s1">1rJY5kRRk7hI0mJSVUXJtF3iOl7tltsBZsNfUegjAY3I5sgFY55KqdX1GgIAIfkECQsAAAAsAAAA</span></span></td>
      </tr>
      <tr>
        <td id="L227" class="blob-num js-line-number" data-line-number="227"></td>
        <td id="LC227" class="blob-code js-file-line"><span class="pl-s2"><span class="pl-s1">ABAACwAABTcgII5kaZ4kcV2EqLJipmnZhWGXaOOitm2aXQ4g7P2Ct2ER4AMul00kj5g0Al8tADY2</span></span></td>
      </tr>
      <tr>
        <td id="L228" class="blob-num js-line-number" data-line-number="228"></td>
        <td id="LC228" class="blob-code js-file-line"><span class="pl-s2"><span class="pl-s1">y6C+4FIIACH5BAkLAAAALAAAAAAQAAsAAAUvICCOZGme5ERRk6iy7qpyHCVStA3gNa/7txxwlwv2</span></span></td>
      </tr>
      <tr>
        <td id="L229" class="blob-num js-line-number" data-line-number="229"></td>
        <td id="LC229" class="blob-code js-file-line"><span class="pl-s2"><span class="pl-s1">isSacYUc+l4tADQGQ1mvpBAAIfkECQsAAAAsAAAAABAACwAABS8gII5kaZ7kRFGTqLLuqnIcJVK0</span></span></td>
      </tr>
      <tr>
        <td id="L230" class="blob-num js-line-number" data-line-number="230"></td>
        <td id="LC230" class="blob-code js-file-line"><span class="pl-s2"><span class="pl-s1">DeA1r/u3HHCXC/aKxJpxhRz6Xi0ANAZDWa+kEAA7AAAAAAAAAAAA</span></span></td>
      </tr>
      <tr>
        <td id="L231" class="blob-num js-line-number" data-line-number="231"></td>
        <td id="LC231" class="blob-code js-file-line"><span class="pl-s2"><span class="pl-s1"><span class="pl-k">IMAGE</span></span>;</span></td>
      </tr>
      <tr>
        <td id="L232" class="blob-num js-line-number" data-line-number="232"></td>
        <td id="LC232" class="blob-code js-file-line"><span class="pl-s2">	<span class="pl-s3">header</span>(<span class="pl-s1"><span class="pl-pds">&#39;</span>Content-type: image/gif<span class="pl-pds">&#39;</span></span>);</span></td>
      </tr>
      <tr>
        <td id="L233" class="blob-num js-line-number" data-line-number="233"></td>
        <td id="LC233" class="blob-code js-file-line"><span class="pl-s2">	<span class="pl-s3">echo</span> <span class="pl-s3">base64_decode</span>(<span class="pl-vo">$data</span>);</span></td>
      </tr>
      <tr>
        <td id="L234" class="blob-num js-line-number" data-line-number="234"></td>
        <td id="LC234" class="blob-code js-file-line"><span class="pl-s2">	<span class="pl-k">exit</span>;</span></td>
      </tr>
      <tr>
        <td id="L235" class="blob-num js-line-number" data-line-number="235"></td>
        <td id="LC235" class="blob-code js-file-line"><span class="pl-s2">}</span></td>
      </tr>
      <tr>
        <td id="L236" class="blob-num js-line-number" data-line-number="236"></td>
        <td id="LC236" class="blob-code js-file-line"><span class="pl-s2"><span class="pl-k">elseif</span> (<span class="pl-s3">isset</span>(<span class="pl-vo">$_GET</span>[<span class="pl-s1"><span class="pl-pds">&#39;</span>ssl_check<span class="pl-pds">&#39;</span></span>]))</span></td>
      </tr>
      <tr>
        <td id="L237" class="blob-num js-line-number" data-line-number="237"></td>
        <td id="LC237" class="blob-code js-file-line"><span class="pl-s2">{</span></td>
      </tr>
      <tr>
        <td id="L238" class="blob-num js-line-number" data-line-number="238"></td>
        <td id="LC238" class="blob-code js-file-line"><span class="pl-s2">	<span class="pl-s3">header</span>(<span class="pl-s1"><span class="pl-pds">&#39;</span>Content-type: text/plain; charset=utf-8<span class="pl-pds">&#39;</span></span>);</span></td>
      </tr>
      <tr>
        <td id="L239" class="blob-num js-line-number" data-line-number="239"></td>
        <td id="LC239" class="blob-code js-file-line"><span class="pl-s2"></span></td>
      </tr>
      <tr>
        <td id="L240" class="blob-num js-line-number" data-line-number="240"></td>
        <td id="LC240" class="blob-code js-file-line"><span class="pl-s2">	<span class="pl-vo">$ch</span> <span class="pl-k">=</span> <span class="pl-s3">curl_init</span>();</span></td>
      </tr>
      <tr>
        <td id="L241" class="blob-num js-line-number" data-line-number="241"></td>
        <td id="LC241" class="blob-code js-file-line"><span class="pl-s2">	<span class="pl-s3">curl_setopt</span>(<span class="pl-vo">$ch</span>, <span class="pl-sc">CURLOPT_URL</span>, <span class="pl-s1"><span class="pl-pds">&#39;</span>https://email.us-east-1.amazonaws.com<span class="pl-pds">&#39;</span></span>);</span></td>
      </tr>
      <tr>
        <td id="L242" class="blob-num js-line-number" data-line-number="242"></td>
        <td id="LC242" class="blob-code js-file-line"><span class="pl-s2">	<span class="pl-s3">curl_setopt</span>(<span class="pl-vo">$ch</span>, <span class="pl-sc">CURLOPT_FRESH_CONNECT</span>, <span class="pl-c1">true</span>);</span></td>
      </tr>
      <tr>
        <td id="L243" class="blob-num js-line-number" data-line-number="243"></td>
        <td id="LC243" class="blob-code js-file-line"><span class="pl-s2">	<span class="pl-s3">curl_setopt</span>(<span class="pl-vo">$ch</span>, <span class="pl-sc">CURLOPT_HEADER</span>, <span class="pl-c1">false</span>);</span></td>
      </tr>
      <tr>
        <td id="L244" class="blob-num js-line-number" data-line-number="244"></td>
        <td id="LC244" class="blob-code js-file-line"><span class="pl-s2">	<span class="pl-s3">curl_setopt</span>(<span class="pl-vo">$ch</span>, <span class="pl-sc">CURLOPT_NOBODY</span>, <span class="pl-c1">true</span>);</span></td>
      </tr>
      <tr>
        <td id="L245" class="blob-num js-line-number" data-line-number="245"></td>
        <td id="LC245" class="blob-code js-file-line"><span class="pl-s2">	<span class="pl-s3">curl_setopt</span>(<span class="pl-vo">$ch</span>, <span class="pl-sc">CURLOPT_RETURNTRANSFER</span>, <span class="pl-c1">true</span>);</span></td>
      </tr>
      <tr>
        <td id="L246" class="blob-num js-line-number" data-line-number="246"></td>
        <td id="LC246" class="blob-code js-file-line"><span class="pl-s2">	<span class="pl-s3">curl_setopt</span>(<span class="pl-vo">$ch</span>, <span class="pl-sc">CURLOPT_TIMEOUT</span>, <span class="pl-c1">5184000</span>);</span></td>
      </tr>
      <tr>
        <td id="L247" class="blob-num js-line-number" data-line-number="247"></td>
        <td id="LC247" class="blob-code js-file-line"><span class="pl-s2">	<span class="pl-s3">curl_setopt</span>(<span class="pl-vo">$ch</span>, <span class="pl-sc">CURLOPT_CONNECTTIMEOUT</span>, <span class="pl-c1">120</span>);</span></td>
      </tr>
      <tr>
        <td id="L248" class="blob-num js-line-number" data-line-number="248"></td>
        <td id="LC248" class="blob-code js-file-line"><span class="pl-s2">	<span class="pl-s3">curl_setopt</span>(<span class="pl-vo">$ch</span>, <span class="pl-sc">CURLOPT_NOSIGNAL</span>, <span class="pl-c1">true</span>);</span></td>
      </tr>
      <tr>
        <td id="L249" class="blob-num js-line-number" data-line-number="249"></td>
        <td id="LC249" class="blob-code js-file-line"><span class="pl-s2">	<span class="pl-s3">curl_setopt</span>(<span class="pl-vo">$ch</span>, <span class="pl-sc">CURLOPT_USERAGENT</span>, <span class="pl-s1"><span class="pl-pds">&#39;</span>aws-sdk-php/compat-www<span class="pl-pds">&#39;</span></span>);</span></td>
      </tr>
      <tr>
        <td id="L250" class="blob-num js-line-number" data-line-number="250"></td>
        <td id="LC250" class="blob-code js-file-line"><span class="pl-s2">	<span class="pl-s3">curl_setopt</span>(<span class="pl-vo">$ch</span>, <span class="pl-sc">CURLOPT_SSL_VERIFYPEER</span>, <span class="pl-c1">true</span>);</span></td>
      </tr>
      <tr>
        <td id="L251" class="blob-num js-line-number" data-line-number="251"></td>
        <td id="LC251" class="blob-code js-file-line"><span class="pl-s2">	<span class="pl-s3">curl_setopt</span>(<span class="pl-vo">$ch</span>, <span class="pl-sc">CURLOPT_SSL_VERIFYHOST</span>, <span class="pl-c1">2</span>);</span></td>
      </tr>
      <tr>
        <td id="L252" class="blob-num js-line-number" data-line-number="252"></td>
        <td id="LC252" class="blob-code js-file-line"><span class="pl-s2">	<span class="pl-s3">curl_setopt</span>(<span class="pl-vo">$ch</span>, <span class="pl-sc">CURLOPT_VERBOSE</span>, <span class="pl-c1">true</span>);</span></td>
      </tr>
      <tr>
        <td id="L253" class="blob-num js-line-number" data-line-number="253"></td>
        <td id="LC253" class="blob-code js-file-line"><span class="pl-s2"></span></td>
      </tr>
      <tr>
        <td id="L254" class="blob-num js-line-number" data-line-number="254"></td>
        <td id="LC254" class="blob-code js-file-line"><span class="pl-s2">	<span class="pl-s3">curl_exec</span>(<span class="pl-vo">$ch</span>);</span></td>
      </tr>
      <tr>
        <td id="L255" class="blob-num js-line-number" data-line-number="255"></td>
        <td id="LC255" class="blob-code js-file-line"><span class="pl-s2">	<span class="pl-s3">echo</span> (<span class="pl-s3">curl_getinfo</span>(<span class="pl-vo">$ch</span>, <span class="pl-sc">CURLINFO_SSL_VERIFYRESULT</span>) <span class="pl-k">===</span> <span class="pl-c1">0</span>) ? <span class="pl-s1"><span class="pl-pds">&#39;</span>false<span class="pl-pds">&#39;</span></span> : <span class="pl-s1"><span class="pl-pds">&#39;</span>true<span class="pl-pds">&#39;</span></span>;</span></td>
      </tr>
      <tr>
        <td id="L256" class="blob-num js-line-number" data-line-number="256"></td>
        <td id="LC256" class="blob-code js-file-line"><span class="pl-s2">	<span class="pl-s3">curl_close</span>(<span class="pl-vo">$ch</span>);</span></td>
      </tr>
      <tr>
        <td id="L257" class="blob-num js-line-number" data-line-number="257"></td>
        <td id="LC257" class="blob-code js-file-line"><span class="pl-s2"></span></td>
      </tr>
      <tr>
        <td id="L258" class="blob-num js-line-number" data-line-number="258"></td>
        <td id="LC258" class="blob-code js-file-line"><span class="pl-s2">	<span class="pl-k">exit</span>;</span></td>
      </tr>
      <tr>
        <td id="L259" class="blob-num js-line-number" data-line-number="259"></td>
        <td id="LC259" class="blob-code js-file-line"><span class="pl-s2">}</span></td>
      </tr>
      <tr>
        <td id="L260" class="blob-num js-line-number" data-line-number="260"></td>
        <td id="LC260" class="blob-code js-file-line"><span class="pl-s2"></span></td>
      </tr>
      <tr>
        <td id="L261" class="blob-num js-line-number" data-line-number="261"></td>
        <td id="LC261" class="blob-code js-file-line"><span class="pl-s2"><span class="pl-c">// Include the compatibility test logic</span></span></td>
      </tr>
      <tr>
        <td id="L262" class="blob-num js-line-number" data-line-number="262"></td>
        <td id="LC262" class="blob-code js-file-line"><span class="pl-s2"><span class="pl-k">require</span> <span class="pl-s3">dirname</span>(<span class="pl-c1">__FILE__</span>) <span class="pl-k">.</span> <span class="pl-sc">DIRECTORY_SEPARATOR</span> <span class="pl-k">.</span> <span class="pl-s1"><span class="pl-pds">&#39;</span>sdk_compatibility.inc.php<span class="pl-pds">&#39;</span></span>;</span></td>
      </tr>
      <tr>
        <td id="L263" class="blob-num js-line-number" data-line-number="263"></td>
        <td id="LC263" class="blob-code js-file-line"><span class="pl-s2"></span></td>
      </tr>
      <tr>
        <td id="L264" class="blob-num js-line-number" data-line-number="264"></td>
        <td id="LC264" class="blob-code js-file-line"><span class="pl-s2"><span class="pl-s3">header</span>(<span class="pl-s1"><span class="pl-pds">&#39;</span>Content-type: text/html; charset=UTF-8<span class="pl-pds">&#39;</span></span>);</span></td>
      </tr>
      <tr>
        <td id="L265" class="blob-num js-line-number" data-line-number="265"></td>
        <td id="LC265" class="blob-code js-file-line"><span class="pl-s2"></span></td>
      </tr>
      <tr>
        <td id="L266" class="blob-num js-line-number" data-line-number="266"></td>
        <td id="LC266" class="blob-code js-file-line"><span class="pl-s2"></span><span class="pl-pse"><span class="pl-s2">?</span>&gt;</span>&lt;!DOCTYPE html&gt;</td>
      </tr>
      <tr>
        <td id="L267" class="blob-num js-line-number" data-line-number="267"></td>
        <td id="LC267" class="blob-code js-file-line">
</td>
      </tr>
      <tr>
        <td id="L268" class="blob-num js-line-number" data-line-number="268"></td>
        <td id="LC268" class="blob-code js-file-line">&lt;<span class="pl-ent">html</span> <span class="pl-e">lang</span>=<span class="pl-s1"><span class="pl-pds">&quot;</span>en<span class="pl-pds">&quot;</span></span>&gt;</td>
      </tr>
      <tr>
        <td id="L269" class="blob-num js-line-number" data-line-number="269"></td>
        <td id="LC269" class="blob-code js-file-line">&lt;<span class="pl-ent">head</span>&gt;</td>
      </tr>
      <tr>
        <td id="L270" class="blob-num js-line-number" data-line-number="270"></td>
        <td id="LC270" class="blob-code js-file-line">&lt;<span class="pl-ent">title</span>&gt;AWS SDK for PHP: Environment Compatibility Test&lt;/<span class="pl-ent">title</span>&gt;</td>
      </tr>
      <tr>
        <td id="L271" class="blob-num js-line-number" data-line-number="271"></td>
        <td id="LC271" class="blob-code js-file-line">&lt;<span class="pl-ent">meta</span> <span class="pl-e">name</span>=<span class="pl-s1"><span class="pl-pds">&quot;</span>ROBOTS<span class="pl-pds">&quot;</span></span> <span class="pl-e">content</span>=<span class="pl-s1"><span class="pl-pds">&quot;</span>NOINDEX, NOFOLLOW, NOARCHIVE<span class="pl-pds">&quot;</span></span> /&gt;</td>
      </tr>
      <tr>
        <td id="L272" class="blob-num js-line-number" data-line-number="272"></td>
        <td id="LC272" class="blob-code js-file-line">
</td>
      </tr>
      <tr>
        <td id="L273" class="blob-num js-line-number" data-line-number="273"></td>
        <td id="LC273" class="blob-code js-file-line"><span class="pl-s2">&lt;<span class="pl-ent">script</span> <span class="pl-e">type</span>=<span class="pl-s1"><span class="pl-pds">&quot;</span>text/javascript<span class="pl-pds">&quot;</span></span> <span class="pl-e">charset</span>=<span class="pl-s1"><span class="pl-pds">&quot;</span>utf-8<span class="pl-pds">&quot;</span></span>&gt;</span></td>
      </tr>
      <tr>
        <td id="L274" class="blob-num js-line-number" data-line-number="274"></td>
        <td id="LC274" class="blob-code js-file-line"><span class="pl-s2"><span class="pl-c">/*!</span></span></td>
      </tr>
      <tr>
        <td id="L275" class="blob-num js-line-number" data-line-number="275"></td>
        <td id="LC275" class="blob-code js-file-line"><span class="pl-s2"><span class="pl-c">  * Reqwest! A x-browser general purpose XHR connection manager</span></span></td>
      </tr>
      <tr>
        <td id="L276" class="blob-num js-line-number" data-line-number="276"></td>
        <td id="LC276" class="blob-code js-file-line"><span class="pl-s2"><span class="pl-c">  * copyright Dustin Diaz 2011</span></span></td>
      </tr>
      <tr>
        <td id="L277" class="blob-num js-line-number" data-line-number="277"></td>
        <td id="LC277" class="blob-code js-file-line"><span class="pl-s2"><span class="pl-c">  * https://github.com/ded/reqwest</span></span></td>
      </tr>
      <tr>
        <td id="L278" class="blob-num js-line-number" data-line-number="278"></td>
        <td id="LC278" class="blob-code js-file-line"><span class="pl-s2"><span class="pl-c">  * license MIT</span></span></td>
      </tr>
      <tr>
        <td id="L279" class="blob-num js-line-number" data-line-number="279"></td>
        <td id="LC279" class="blob-code js-file-line"><span class="pl-s2"><span class="pl-c">  */</span></span></td>
      </tr>
      <tr>
        <td id="L280" class="blob-num js-line-number" data-line-number="280"></td>
        <td id="LC280" class="blob-code js-file-line"><span class="pl-s2">!function(window){function serial(a){var b=a.name;if(a.disabled||!b)return&quot;&quot;;b=enc(b);switch(a.tagName.toLowerCase()){case&quot;input&quot;:switch(a.type){case&quot;reset&quot;:case&quot;button&quot;:case&quot;image&quot;:case&quot;file&quot;:return&quot;&quot;;case&quot;checkbox&quot;:case&quot;radio&quot;:return a.checked?b+&quot;=&quot;+(a.value?enc(a.value):!0)+&quot;&amp;&quot;:&quot;&quot;;default:return b+&quot;=&quot;+(a.value?enc(a.value):&quot;&quot;)+&quot;&amp;&quot;}break;case&quot;textarea&quot;:return b+&quot;=&quot;+enc(a.value)+&quot;&amp;&quot;;case&quot;select&quot;:return b+&quot;=&quot;+enc(a.options[a.selectedIndex].value)+&quot;&amp;&quot;}return&quot;&quot;}function enc(a){return encodeURIComponent(a)}function reqwest(a,b){return new Reqwest(a,b)}function init(o,fn){function error(a){o.error&amp;&amp;o.error(a),complete(a)}function success(resp){o.timeout&amp;&amp;clearTimeout(self.timeout)&amp;&amp;(self.timeout=null);var r=resp.responseText;if(r)switch(type){case&quot;json&quot;:resp=window.JSON?window.JSON.parse(r):eval(&quot;(&quot;+r+&quot;)&quot;);break;case&quot;js&quot;:resp=eval(r);break;case&quot;html&quot;:resp=r}fn(resp),o.success&amp;&amp;o.success(resp),complete(resp)}function complete(a){o.complete&amp;&amp;o.complete(a)}this.url=typeof o==&quot;string&quot;?o:o.url,this.timeout=null;var type=o.type||setType(this.url),self=this;fn=fn||function(){},o.timeout&amp;&amp;(this.timeout=setTimeout(function(){self.abort(),error()},o.timeout)),this.request=getRequest(o,success,error)}function setType(a){return/\.json$/.test(a)?&quot;json&quot;:/\.jsonp$/.test(a)?&quot;jsonp&quot;:/\.js$/.test(a)?&quot;js&quot;:/\.html?$/.test(a)?&quot;html&quot;:/\.xml$/.test(a)?&quot;xml&quot;:&quot;js&quot;}function Reqwest(a,b){this.o=a,this.fn=b,init.apply(this,arguments)}function getRequest(a,b,c){if(a.type!=&quot;jsonp&quot;){var f=xhr();f.open(a.method||&quot;GET&quot;,typeof a==&quot;string&quot;?a:a.url,!0),setHeaders(f,a),f.onreadystatechange=readyState(f,b,c),a.before&amp;&amp;a.before(f),f.send(a.data||null);return f}var d=doc.createElement(&quot;script&quot;);window[getCallbackName(a)]=generalCallback,d.type=&quot;text/javascript&quot;,d.src=a.url,d.async=!0;var e=function(){a.success&amp;&amp;a.success(lastValue),lastValue=undefined,head.removeChild(d)};d.onload=e,d.onreadystatechange=function(){/^loaded|complete$/.test(d.readyState)&amp;&amp;e()},head.appendChild(d)}function generalCallback(a){lastValue=a}function getCallbackName(a){var b=a.jsonpCallback||&quot;callback&quot;;if(a.url.slice(-(b.length+2))==b+&quot;=?&quot;){var c=&quot;reqwest_&quot;+uniqid++;a.url=a.url.substr(0,a.url.length-1)+c;return c}var d=new RegExp(b+&quot;=([\\w]+)&quot;);return a.url.match(d)[1]}function setHeaders(a,b){var c=b.headers||{};c.Accept=c.Accept||&quot;text/javascript, text/html, application/xml, text/xml, */*&quot;,b.crossOrigin||(c[&quot;X-Requested-With&quot;]=c[&quot;X-Requested-With&quot;]||&quot;XMLHttpRequest&quot;);if(b.data){c[&quot;Content-type&quot;]=c[&quot;Content-type&quot;]||&quot;application/x-www-form-urlencoded&quot;;for(var d in c)c.hasOwnProperty(d)&amp;&amp;a.setRequestHeader(d,c[d],!1)}}function readyState(a,b,c){return function(){a&amp;&amp;a.readyState==4&amp;&amp;(twoHundo.test(a.status)?b(a):c(a))}}var v=window.v;!v&amp;&amp;typeof require!=&quot;undefined&quot;&amp;&amp;(v=require(&quot;valentine&quot;));var twoHundo=/^20\d$/,doc=document,byTag=&quot;getElementsByTagName&quot;,head=doc[byTag](&quot;head&quot;)[0],xhr=&quot;XMLHttpRequest&quot;in window?function(){return new XMLHttpRequest}:function(){return new ActiveXObject(&quot;Microsoft.XMLHTTP&quot;)},uniqid=0,lastValue;Reqwest.prototype={abort:function(){this.request.abort()},retry:function(){init.call(this,this.o,this.fn)}},reqwest.serialize=function(a){var b=a[byTag](&quot;input&quot;),c=a[byTag](&quot;select&quot;),d=a[byTag](&quot;textarea&quot;);return(v(b).chain().toArray().map(serial).value().join(&quot;&quot;)+v(c).chain().toArray().map(serial).value().join(&quot;&quot;)+v(d).chain().toArray().map(serial).value().join(&quot;&quot;)).replace(/&amp;$/,&quot;&quot;)},reqwest.serializeArray=function(a){for(var b=this.serialize(a).split(&quot;&amp;&quot;),c=0,d=b.length,e=[],f;c&lt;d;c++)b[c]&amp;&amp;(f=b[c].split(&quot;=&quot;))&amp;&amp;e.push({name:f[0],value:f[1]});return e};var old=window.reqwest;reqwest.noConflict=function(){window.reqwest=old;return this},window.reqwest=reqwest}(this)</span></td>
      </tr>
      <tr>
        <td id="L281" class="blob-num js-line-number" data-line-number="281"></td>
        <td id="LC281" class="blob-code js-file-line"><span class="pl-s2">&lt;/<span class="pl-ent">script</span>&gt;</span></td>
      </tr>
      <tr>
        <td id="L282" class="blob-num js-line-number" data-line-number="282"></td>
        <td id="LC282" class="blob-code js-file-line">
</td>
      </tr>
      <tr>
        <td id="L283" class="blob-num js-line-number" data-line-number="283"></td>
        <td id="LC283" class="blob-code js-file-line"><span class="pl-s2">&lt;<span class="pl-ent">style</span> <span class="pl-e">type</span>=<span class="pl-s1"><span class="pl-pds">&quot;</span>text/css<span class="pl-pds">&quot;</span></span>&gt;</span></td>
      </tr>
      <tr>
        <td id="L284" class="blob-num js-line-number" data-line-number="284"></td>
        <td id="LC284" class="blob-code js-file-line"><span class="pl-s2"><span class="pl-ent">body</span> {</span></td>
      </tr>
      <tr>
        <td id="L285" class="blob-num js-line-number" data-line-number="285"></td>
        <td id="LC285" class="blob-code js-file-line"><span class="pl-s2">	<span class="pl-mp"><span class="pl-s3">font</span></span>:<span class="pl-c1">14<span class="pl-k">px</span></span>/<span class="pl-c1">1.4<span class="pl-k">em</span></span> <span class="pl-s1"><span class="pl-pds">&quot;</span>Helvetica Neue<span class="pl-pds">&quot;</span></span>, <span class="pl-sc">Helvetica</span>, <span class="pl-s1"><span class="pl-pds">&quot;</span>Lucida Grande<span class="pl-pds">&quot;</span></span>, Roboto, <span class="pl-s1"><span class="pl-pds">&quot;</span>Droid Sans<span class="pl-pds">&quot;</span></span>, Ubuntu, <span class="pl-sc">Verdana</span>, <span class="pl-sc">Arial</span>, Clean, Sans, <span class="pl-sc">sans-serif</span>;</span></td>
      </tr>
      <tr>
        <td id="L286" class="blob-num js-line-number" data-line-number="286"></td>
        <td id="LC286" class="blob-code js-file-line"><span class="pl-s2">	<span class="pl-mp"><span class="pl-s3">letter-spacing</span></span>:<span class="pl-c1">0<span class="pl-k">px</span></span>;</span></td>
      </tr>
      <tr>
        <td id="L287" class="blob-num js-line-number" data-line-number="287"></td>
        <td id="LC287" class="blob-code js-file-line"><span class="pl-s2">	<span class="pl-mp"><span class="pl-s3">color</span></span>:<span class="pl-c1">#333</span>;</span></td>
      </tr>
      <tr>
        <td id="L288" class="blob-num js-line-number" data-line-number="288"></td>
        <td id="LC288" class="blob-code js-file-line"><span class="pl-s2">	<span class="pl-mp"><span class="pl-s3">margin</span></span>:<span class="pl-c1">0</span>;</span></td>
      </tr>
      <tr>
        <td id="L289" class="blob-num js-line-number" data-line-number="289"></td>
        <td id="LC289" class="blob-code js-file-line"><span class="pl-s2">	<span class="pl-mp"><span class="pl-s3">padding</span></span>:<span class="pl-c1">0</span>;</span></td>
      </tr>
      <tr>
        <td id="L290" class="blob-num js-line-number" data-line-number="290"></td>
        <td id="LC290" class="blob-code js-file-line"><span class="pl-s2">	<span class="pl-mp"><span class="pl-s3">background</span></span>:<span class="pl-c1">#fff</span> <span class="pl-s3">url</span>(<span class="pl-v">&lt;?php</span> <span class="pl-v">echo</span> <span class="pl-v">pathinfo(__FILE__,</span> <span class="pl-v">PATHINFO_BASENAME</span>); ?&gt;?<span class="pl-mp"><span class="pl-s3">background</span></span>) <span class="pl-mp"><span class="pl-s3">repeat</span>-<span class="pl-s3">x</span></span> <span class="pl-mp"><span class="pl-s3">top</span></span> <span class="pl-mp"><span class="pl-s3">left</span></span>;</span></td>
      </tr>
      <tr>
        <td id="L291" class="blob-num js-line-number" data-line-number="291"></td>
        <td id="LC291" class="blob-code js-file-line"><span class="pl-s2">}</span></td>
      </tr>
      <tr>
        <td id="L292" class="blob-num js-line-number" data-line-number="292"></td>
        <td id="LC292" class="blob-code js-file-line"><span class="pl-s2"></span></td>
      </tr>
      <tr>
        <td id="L293" class="blob-num js-line-number" data-line-number="293"></td>
        <td id="LC293" class="blob-code js-file-line"><span class="pl-s2"><span class="pl-ent">div</span><span class="pl-e">#site</span> {</span></td>
      </tr>
      <tr>
        <td id="L294" class="blob-num js-line-number" data-line-number="294"></td>
        <td id="LC294" class="blob-code js-file-line"><span class="pl-s2">	<span class="pl-mp"><span class="pl-s3">width</span></span>:<span class="pl-c1">650<span class="pl-k">px</span></span>;</span></td>
      </tr>
      <tr>
        <td id="L295" class="blob-num js-line-number" data-line-number="295"></td>
        <td id="LC295" class="blob-code js-file-line"><span class="pl-s2">	<span class="pl-mp"><span class="pl-s3">margin</span></span>:<span class="pl-c1">20<span class="pl-k">px</span></span> <span class="pl-sc">auto</span> <span class="pl-c1">0</span> <span class="pl-sc">auto</span>;</span></td>
      </tr>
      <tr>
        <td id="L296" class="blob-num js-line-number" data-line-number="296"></td>
        <td id="LC296" class="blob-code js-file-line"><span class="pl-s2">}</span></td>
      </tr>
      <tr>
        <td id="L297" class="blob-num js-line-number" data-line-number="297"></td>
        <td id="LC297" class="blob-code js-file-line"><span class="pl-s2"></span></td>
      </tr>
      <tr>
        <td id="L298" class="blob-num js-line-number" data-line-number="298"></td>
        <td id="LC298" class="blob-code js-file-line"><span class="pl-s2"><span class="pl-ent">a</span> {</span></td>
      </tr>
      <tr>
        <td id="L299" class="blob-num js-line-number" data-line-number="299"></td>
        <td id="LC299" class="blob-code js-file-line"><span class="pl-s2">	<span class="pl-mp"><span class="pl-s3">color</span></span>: <span class="pl-c1">#326EA1</span>;</span></td>
      </tr>
      <tr>
        <td id="L300" class="blob-num js-line-number" data-line-number="300"></td>
        <td id="LC300" class="blob-code js-file-line"><span class="pl-s2">	<span class="pl-mp"><span class="pl-s3">text-decoration</span></span>: <span class="pl-sc">underline</span>;</span></td>
      </tr>
      <tr>
        <td id="L301" class="blob-num js-line-number" data-line-number="301"></td>
        <td id="LC301" class="blob-code js-file-line"><span class="pl-s2">	<span class="pl-mp"><span class="pl-s3">padding</span></span>: <span class="pl-c1">1<span class="pl-k">px</span></span> <span class="pl-c1">2<span class="pl-k">px</span></span>;</span></td>
      </tr>
      <tr>
        <td id="L302" class="blob-num js-line-number" data-line-number="302"></td>
        <td id="LC302" class="blob-code js-file-line"><span class="pl-s2">	<span class="pl-mp">-webkit-<span class="pl-s3">transition</span></span>: background-color <span class="pl-c1">0.15<span class="pl-k">s</span></span>;</span></td>
      </tr>
      <tr>
        <td id="L303" class="blob-num js-line-number" data-line-number="303"></td>
        <td id="LC303" class="blob-code js-file-line"><span class="pl-s2">	<span class="pl-mp">-webkit-<span class="pl-s3">transition</span></span>: color <span class="pl-c1">0.15<span class="pl-k">s</span></span>;</span></td>
      </tr>
      <tr>
        <td id="L304" class="blob-num js-line-number" data-line-number="304"></td>
        <td id="LC304" class="blob-code js-file-line"><span class="pl-s2">	<span class="pl-mp">-moz-<span class="pl-s3">transition</span></span>: background-color <span class="pl-c1">0.15<span class="pl-k">s</span></span>;</span></td>
      </tr>
      <tr>
        <td id="L305" class="blob-num js-line-number" data-line-number="305"></td>
        <td id="LC305" class="blob-code js-file-line"><span class="pl-s2">	<span class="pl-mp">-moz-<span class="pl-s3">transition</span></span>: color <span class="pl-c1">0.15<span class="pl-k">s</span></span>;</span></td>
      </tr>
      <tr>
        <td id="L306" class="blob-num js-line-number" data-line-number="306"></td>
        <td id="LC306" class="blob-code js-file-line"><span class="pl-s2">	<span class="pl-mp"><span class="pl-s3">transition</span></span>: background-color <span class="pl-c1">0.15<span class="pl-k">s</span></span>;</span></td>
      </tr>
      <tr>
        <td id="L307" class="blob-num js-line-number" data-line-number="307"></td>
        <td id="LC307" class="blob-code js-file-line"><span class="pl-s2">	<span class="pl-mp"><span class="pl-s3">transition</span></span>: color <span class="pl-c1">0.15<span class="pl-k">s</span></span>;</span></td>
      </tr>
      <tr>
        <td id="L308" class="blob-num js-line-number" data-line-number="308"></td>
        <td id="LC308" class="blob-code js-file-line"><span class="pl-s2">	<span class="pl-mp">-webkit-<span class="pl-s3">border-radius</span></span>: <span class="pl-c1">2<span class="pl-k">px</span></span>;</span></td>
      </tr>
      <tr>
        <td id="L309" class="blob-num js-line-number" data-line-number="309"></td>
        <td id="LC309" class="blob-code js-file-line"><span class="pl-s2">	<span class="pl-mp">-moz-<span class="pl-s3">border-radius</span></span>: <span class="pl-c1">2<span class="pl-k">px</span></span>;</span></td>
      </tr>
      <tr>
        <td id="L310" class="blob-num js-line-number" data-line-number="310"></td>
        <td id="LC310" class="blob-code js-file-line"><span class="pl-s2">	<span class="pl-mp"><span class="pl-s3">border-radius</span></span>: <span class="pl-c1">2<span class="pl-k">px</span></span>;</span></td>
      </tr>
      <tr>
        <td id="L311" class="blob-num js-line-number" data-line-number="311"></td>
        <td id="LC311" class="blob-code js-file-line"><span class="pl-s2">}</span></td>
      </tr>
      <tr>
        <td id="L312" class="blob-num js-line-number" data-line-number="312"></td>
        <td id="LC312" class="blob-code js-file-line"><span class="pl-s2"></span></td>
      </tr>
      <tr>
        <td id="L313" class="blob-num js-line-number" data-line-number="313"></td>
        <td id="LC313" class="blob-code js-file-line"><span class="pl-s2"><span class="pl-ent">a</span><span class="pl-e">:hover</span>, <span class="pl-ent">a</span><span class="pl-e">.hover</span> {</span></td>
      </tr>
      <tr>
        <td id="L314" class="blob-num js-line-number" data-line-number="314"></td>
        <td id="LC314" class="blob-code js-file-line"><span class="pl-s2">	<span class="pl-mp"><span class="pl-s3">color</span></span>: <span class="pl-c1">#fff</span>;</span></td>
      </tr>
      <tr>
        <td id="L315" class="blob-num js-line-number" data-line-number="315"></td>
        <td id="LC315" class="blob-code js-file-line"><span class="pl-s2">	<span class="pl-mp"><span class="pl-s3">background-color</span></span>: <span class="pl-c1">#333</span>;</span></td>
      </tr>
      <tr>
        <td id="L316" class="blob-num js-line-number" data-line-number="316"></td>
        <td id="LC316" class="blob-code js-file-line"><span class="pl-s2">	<span class="pl-mp"><span class="pl-s3">text-decoration</span></span>: <span class="pl-sc">none</span>;</span></td>
      </tr>
      <tr>
        <td id="L317" class="blob-num js-line-number" data-line-number="317"></td>
        <td id="LC317" class="blob-code js-file-line"><span class="pl-s2">	<span class="pl-mp"><span class="pl-s3">padding</span></span>: <span class="pl-c1">1<span class="pl-k">px</span></span> <span class="pl-c1">2<span class="pl-k">px</span></span>;</span></td>
      </tr>
      <tr>
        <td id="L318" class="blob-num js-line-number" data-line-number="318"></td>
        <td id="LC318" class="blob-code js-file-line"><span class="pl-s2">}</span></td>
      </tr>
      <tr>
        <td id="L319" class="blob-num js-line-number" data-line-number="319"></td>
        <td id="LC319" class="blob-code js-file-line"><span class="pl-s2"></span></td>
      </tr>
      <tr>
        <td id="L320" class="blob-num js-line-number" data-line-number="320"></td>
        <td id="LC320" class="blob-code js-file-line"><span class="pl-s2"><span class="pl-ent">p</span> {</span></td>
      </tr>
      <tr>
        <td id="L321" class="blob-num js-line-number" data-line-number="321"></td>
        <td id="LC321" class="blob-code js-file-line"><span class="pl-s2">	<span class="pl-mp"><span class="pl-s3">margin</span></span>:<span class="pl-c1">0</span>;</span></td>
      </tr>
      <tr>
        <td id="L322" class="blob-num js-line-number" data-line-number="322"></td>
        <td id="LC322" class="blob-code js-file-line"><span class="pl-s2">	<span class="pl-mp"><span class="pl-s3">padding</span></span>:<span class="pl-c1">5<span class="pl-k">px</span></span> <span class="pl-c1">0</span>;</span></td>
      </tr>
      <tr>
        <td id="L323" class="blob-num js-line-number" data-line-number="323"></td>
        <td id="LC323" class="blob-code js-file-line"><span class="pl-s2">}</span></td>
      </tr>
      <tr>
        <td id="L324" class="blob-num js-line-number" data-line-number="324"></td>
        <td id="LC324" class="blob-code js-file-line"><span class="pl-s2"></span></td>
      </tr>
      <tr>
        <td id="L325" class="blob-num js-line-number" data-line-number="325"></td>
        <td id="LC325" class="blob-code js-file-line"><span class="pl-s2"><span class="pl-ent">em</span> {</span></td>
      </tr>
      <tr>
        <td id="L326" class="blob-num js-line-number" data-line-number="326"></td>
        <td id="LC326" class="blob-code js-file-line"><span class="pl-s2">	<span class="pl-mp"><span class="pl-s3">font-style</span></span>:<span class="pl-sc">normal</span>;</span></td>
      </tr>
      <tr>
        <td id="L327" class="blob-num js-line-number" data-line-number="327"></td>
        <td id="LC327" class="blob-code js-file-line"><span class="pl-s2">	<span class="pl-mp"><span class="pl-s3">background-color</span></span>:<span class="pl-c1">#ffc</span>;</span></td>
      </tr>
      <tr>
        <td id="L328" class="blob-num js-line-number" data-line-number="328"></td>
        <td id="LC328" class="blob-code js-file-line"><span class="pl-s2">}</span></td>
      </tr>
      <tr>
        <td id="L329" class="blob-num js-line-number" data-line-number="329"></td>
        <td id="LC329" class="blob-code js-file-line"><span class="pl-s2"></span></td>
      </tr>
      <tr>
        <td id="L330" class="blob-num js-line-number" data-line-number="330"></td>
        <td id="LC330" class="blob-code js-file-line"><span class="pl-s2"><span class="pl-ent">ul</span>, <span class="pl-ent">ol</span> {</span></td>
      </tr>
      <tr>
        <td id="L331" class="blob-num js-line-number" data-line-number="331"></td>
        <td id="LC331" class="blob-code js-file-line"><span class="pl-s2">	<span class="pl-mp"><span class="pl-s3">margin</span></span>:<span class="pl-c1">10<span class="pl-k">px</span></span> <span class="pl-c1">0</span> <span class="pl-c1">10<span class="pl-k">px</span></span> <span class="pl-c1">20<span class="pl-k">px</span></span>;</span></td>
      </tr>
      <tr>
        <td id="L332" class="blob-num js-line-number" data-line-number="332"></td>
        <td id="LC332" class="blob-code js-file-line"><span class="pl-s2">	<span class="pl-mp"><span class="pl-s3">padding</span></span>:<span class="pl-c1">0</span> <span class="pl-c1">0</span> <span class="pl-c1">0</span> <span class="pl-c1">15<span class="pl-k">px</span></span>;</span></td>
      </tr>
      <tr>
        <td id="L333" class="blob-num js-line-number" data-line-number="333"></td>
        <td id="LC333" class="blob-code js-file-line"><span class="pl-s2">}</span></td>
      </tr>
      <tr>
        <td id="L334" class="blob-num js-line-number" data-line-number="334"></td>
        <td id="LC334" class="blob-code js-file-line"><span class="pl-s2"></span></td>
      </tr>
      <tr>
        <td id="L335" class="blob-num js-line-number" data-line-number="335"></td>
        <td id="LC335" class="blob-code js-file-line"><span class="pl-s2"><span class="pl-ent">ul</span> <span class="pl-ent">li</span>, <span class="pl-ent">ol</span> <span class="pl-ent">li</span> {</span></td>
      </tr>
      <tr>
        <td id="L336" class="blob-num js-line-number" data-line-number="336"></td>
        <td id="LC336" class="blob-code js-file-line"><span class="pl-s2">	<span class="pl-mp"><span class="pl-s3">margin</span></span>:<span class="pl-c1">0</span> <span class="pl-c1">0</span> <span class="pl-c1">4<span class="pl-k">px</span></span> <span class="pl-c1">0</span>;</span></td>
      </tr>
      <tr>
        <td id="L337" class="blob-num js-line-number" data-line-number="337"></td>
        <td id="LC337" class="blob-code js-file-line"><span class="pl-s2">	<span class="pl-mp"><span class="pl-s3">padding</span></span>:<span class="pl-c1">0</span> <span class="pl-c1">0</span> <span class="pl-c1">0</span> <span class="pl-c1">3<span class="pl-k">px</span></span>;</span></td>
      </tr>
      <tr>
        <td id="L338" class="blob-num js-line-number" data-line-number="338"></td>
        <td id="LC338" class="blob-code js-file-line"><span class="pl-s2">}</span></td>
      </tr>
      <tr>
        <td id="L339" class="blob-num js-line-number" data-line-number="339"></td>
        <td id="LC339" class="blob-code js-file-line"><span class="pl-s2"></span></td>
      </tr>
      <tr>
        <td id="L340" class="blob-num js-line-number" data-line-number="340"></td>
        <td id="LC340" class="blob-code js-file-line"><span class="pl-s2"><span class="pl-ent">h2</span> {</span></td>
      </tr>
      <tr>
        <td id="L341" class="blob-num js-line-number" data-line-number="341"></td>
        <td id="LC341" class="blob-code js-file-line"><span class="pl-s2">	<span class="pl-mp"><span class="pl-s3">font-size</span></span>:<span class="pl-c1">18<span class="pl-k">px</span></span>;</span></td>
      </tr>
      <tr>
        <td id="L342" class="blob-num js-line-number" data-line-number="342"></td>
        <td id="LC342" class="blob-code js-file-line"><span class="pl-s2">	<span class="pl-mp"><span class="pl-s3">padding</span></span>:<span class="pl-c1">0</span>;</span></td>
      </tr>
      <tr>
        <td id="L343" class="blob-num js-line-number" data-line-number="343"></td>
        <td id="LC343" class="blob-code js-file-line"><span class="pl-s2">	<span class="pl-mp"><span class="pl-s3">margin</span></span>:<span class="pl-c1">0</span> <span class="pl-c1">0</span> <span class="pl-c1">10<span class="pl-k">px</span></span> <span class="pl-c1">0</span>;</span></td>
      </tr>
      <tr>
        <td id="L344" class="blob-num js-line-number" data-line-number="344"></td>
        <td id="LC344" class="blob-code js-file-line"><span class="pl-s2">}</span></td>
      </tr>
      <tr>
        <td id="L345" class="blob-num js-line-number" data-line-number="345"></td>
        <td id="LC345" class="blob-code js-file-line"><span class="pl-s2"></span></td>
      </tr>
      <tr>
        <td id="L346" class="blob-num js-line-number" data-line-number="346"></td>
        <td id="LC346" class="blob-code js-file-line"><span class="pl-s2"><span class="pl-ent">h3</span> {</span></td>
      </tr>
      <tr>
        <td id="L347" class="blob-num js-line-number" data-line-number="347"></td>
        <td id="LC347" class="blob-code js-file-line"><span class="pl-s2">	<span class="pl-mp"><span class="pl-s3">font-size</span></span>:<span class="pl-c1">16<span class="pl-k">px</span></span>;</span></td>
      </tr>
      <tr>
        <td id="L348" class="blob-num js-line-number" data-line-number="348"></td>
        <td id="LC348" class="blob-code js-file-line"><span class="pl-s2">	<span class="pl-mp"><span class="pl-s3">padding</span></span>:<span class="pl-c1">0</span>;</span></td>
      </tr>
      <tr>
        <td id="L349" class="blob-num js-line-number" data-line-number="349"></td>
        <td id="LC349" class="blob-code js-file-line"><span class="pl-s2">	<span class="pl-mp"><span class="pl-s3">margin</span></span>:<span class="pl-c1">20<span class="pl-k">px</span></span> <span class="pl-c1">0</span> <span class="pl-c1">5<span class="pl-k">px</span></span> <span class="pl-c1">0</span>;</span></td>
      </tr>
      <tr>
        <td id="L350" class="blob-num js-line-number" data-line-number="350"></td>
        <td id="LC350" class="blob-code js-file-line"><span class="pl-s2">}</span></td>
      </tr>
      <tr>
        <td id="L351" class="blob-num js-line-number" data-line-number="351"></td>
        <td id="LC351" class="blob-code js-file-line"><span class="pl-s2"></span></td>
      </tr>
      <tr>
        <td id="L352" class="blob-num js-line-number" data-line-number="352"></td>
        <td id="LC352" class="blob-code js-file-line"><span class="pl-s2"><span class="pl-ent">h4</span> {</span></td>
      </tr>
      <tr>
        <td id="L353" class="blob-num js-line-number" data-line-number="353"></td>
        <td id="LC353" class="blob-code js-file-line"><span class="pl-s2">	<span class="pl-mp"><span class="pl-s3">font-size</span></span>:<span class="pl-c1">14<span class="pl-k">px</span></span>;</span></td>
      </tr>
      <tr>
        <td id="L354" class="blob-num js-line-number" data-line-number="354"></td>
        <td id="LC354" class="blob-code js-file-line"><span class="pl-s2">	<span class="pl-mp"><span class="pl-s3">padding</span></span>:<span class="pl-c1">0</span>;</span></td>
      </tr>
      <tr>
        <td id="L355" class="blob-num js-line-number" data-line-number="355"></td>
        <td id="LC355" class="blob-code js-file-line"><span class="pl-s2">	<span class="pl-mp"><span class="pl-s3">margin</span></span>:<span class="pl-c1">15<span class="pl-k">px</span></span> <span class="pl-c1">0</span> <span class="pl-c1">5<span class="pl-k">px</span></span> <span class="pl-c1">0</span>;</span></td>
      </tr>
      <tr>
        <td id="L356" class="blob-num js-line-number" data-line-number="356"></td>
        <td id="LC356" class="blob-code js-file-line"><span class="pl-s2">}</span></td>
      </tr>
      <tr>
        <td id="L357" class="blob-num js-line-number" data-line-number="357"></td>
        <td id="LC357" class="blob-code js-file-line"><span class="pl-s2"></span></td>
      </tr>
      <tr>
        <td id="L358" class="blob-num js-line-number" data-line-number="358"></td>
        <td id="LC358" class="blob-code js-file-line"><span class="pl-s2"><span class="pl-ent">pre</span>, <span class="pl-ent">code</span> {</span></td>
      </tr>
      <tr>
        <td id="L359" class="blob-num js-line-number" data-line-number="359"></td>
        <td id="LC359" class="blob-code js-file-line"><span class="pl-s2">    <span class="pl-mp"><span class="pl-s3">font-family</span></span>: <span class="pl-s1"><span class="pl-pds">&quot;</span>Panic Sans<span class="pl-pds">&quot;</span></span>, <span class="pl-s1"><span class="pl-pds">&quot;</span>Bitstream Vera Sans Mono<span class="pl-pds">&quot;</span></span>, Monaco, Consolas, <span class="pl-s1"><span class="pl-pds">&quot;</span>Andale Mono<span class="pl-pds">&quot;</span></span>, <span class="pl-sc">monospace</span>;</span></td>
      </tr>
      <tr>
        <td id="L360" class="blob-num js-line-number" data-line-number="360"></td>
        <td id="LC360" class="blob-code js-file-line"><span class="pl-s2">	<span class="pl-mp"><span class="pl-s3">background-color</span></span>: <span class="pl-c1">#F0F0F0</span>;</span></td>
      </tr>
      <tr>
        <td id="L361" class="blob-num js-line-number" data-line-number="361"></td>
        <td id="LC361" class="blob-code js-file-line"><span class="pl-s2">	<span class="pl-mp"><span class="pl-s3">border-radius</span></span>: <span class="pl-c1">3<span class="pl-k">px</span></span> <span class="pl-c1">3<span class="pl-k">px</span></span> <span class="pl-c1">3<span class="pl-k">px</span></span> <span class="pl-c1">3<span class="pl-k">px</span></span>;</span></td>
      </tr>
      <tr>
        <td id="L362" class="blob-num js-line-number" data-line-number="362"></td>
        <td id="LC362" class="blob-code js-file-line"><span class="pl-s2">	<span class="pl-mp"><span class="pl-s3">padding</span></span>: <span class="pl-c1">0</span> <span class="pl-c1">3<span class="pl-k">px</span></span>;</span></td>
      </tr>
      <tr>
        <td id="L363" class="blob-num js-line-number" data-line-number="363"></td>
        <td id="LC363" class="blob-code js-file-line"><span class="pl-s2">	<span class="pl-mp"><span class="pl-s3">font-size</span></span>: <span class="pl-c1">1<span class="pl-k">em</span></span>;</span></td>
      </tr>
      <tr>
        <td id="L364" class="blob-num js-line-number" data-line-number="364"></td>
        <td id="LC364" class="blob-code js-file-line"><span class="pl-s2">}</span></td>
      </tr>
      <tr>
        <td id="L365" class="blob-num js-line-number" data-line-number="365"></td>
        <td id="LC365" class="blob-code js-file-line"><span class="pl-s2"></span></td>
      </tr>
      <tr>
        <td id="L366" class="blob-num js-line-number" data-line-number="366"></td>
        <td id="LC366" class="blob-code js-file-line"><span class="pl-s2"><span class="pl-ent">em</span> <span class="pl-ent">strong</span> {</span></td>
      </tr>
      <tr>
        <td id="L367" class="blob-num js-line-number" data-line-number="367"></td>
        <td id="LC367" class="blob-code js-file-line"><span class="pl-s2">    <span class="pl-mp"><span class="pl-s3">text-transform</span></span>: <span class="pl-sc">uppercase</span>;</span></td>
      </tr>
      <tr>
        <td id="L368" class="blob-num js-line-number" data-line-number="368"></td>
        <td id="LC368" class="blob-code js-file-line"><span class="pl-s2">}</span></td>
      </tr>
      <tr>
        <td id="L369" class="blob-num js-line-number" data-line-number="369"></td>
        <td id="LC369" class="blob-code js-file-line"><span class="pl-s2"></span></td>
      </tr>
      <tr>
        <td id="L370" class="blob-num js-line-number" data-line-number="370"></td>
        <td id="LC370" class="blob-code js-file-line"><span class="pl-s2"><span class="pl-ent">table</span><span class="pl-e">.chart</span> {</span></td>
      </tr>
      <tr>
        <td id="L371" class="blob-num js-line-number" data-line-number="371"></td>
        <td id="LC371" class="blob-code js-file-line"><span class="pl-s2">	<span class="pl-mp"><span class="pl-s3">border-collapse</span></span>:<span class="pl-sc">collapse</span>;</span></td>
      </tr>
      <tr>
        <td id="L372" class="blob-num js-line-number" data-line-number="372"></td>
        <td id="LC372" class="blob-code js-file-line"><span class="pl-s2">}</span></td>
      </tr>
      <tr>
        <td id="L373" class="blob-num js-line-number" data-line-number="373"></td>
        <td id="LC373" class="blob-code js-file-line"><span class="pl-s2"></span></td>
      </tr>
      <tr>
        <td id="L374" class="blob-num js-line-number" data-line-number="374"></td>
        <td id="LC374" class="blob-code js-file-line"><span class="pl-s2"><span class="pl-ent">table</span><span class="pl-e">.chart</span> <span class="pl-ent">th</span> {</span></td>
      </tr>
      <tr>
        <td id="L375" class="blob-num js-line-number" data-line-number="375"></td>
        <td id="LC375" class="blob-code js-file-line"><span class="pl-s2">	<span class="pl-mp"><span class="pl-s3">background-color</span></span>:<span class="pl-c1">#eee</span>;</span></td>
      </tr>
      <tr>
        <td id="L376" class="blob-num js-line-number" data-line-number="376"></td>
        <td id="LC376" class="blob-code js-file-line"><span class="pl-s2">	<span class="pl-mp"><span class="pl-s3">padding</span></span>:<span class="pl-c1">2<span class="pl-k">px</span></span> <span class="pl-c1">3<span class="pl-k">px</span></span>;</span></td>
      </tr>
      <tr>
        <td id="L377" class="blob-num js-line-number" data-line-number="377"></td>
        <td id="LC377" class="blob-code js-file-line"><span class="pl-s2">	<span class="pl-mp"><span class="pl-s3">border</span></span>:<span class="pl-c1">1<span class="pl-k">px</span></span> <span class="pl-sc">solid</span> <span class="pl-c1">#fff</span>;</span></td>
      </tr>
      <tr>
        <td id="L378" class="blob-num js-line-number" data-line-number="378"></td>
        <td id="LC378" class="blob-code js-file-line"><span class="pl-s2">}</span></td>
      </tr>
      <tr>
        <td id="L379" class="blob-num js-line-number" data-line-number="379"></td>
        <td id="LC379" class="blob-code js-file-line"><span class="pl-s2"></span></td>
      </tr>
      <tr>
        <td id="L380" class="blob-num js-line-number" data-line-number="380"></td>
        <td id="LC380" class="blob-code js-file-line"><span class="pl-s2"><span class="pl-ent">table</span><span class="pl-e">.chart</span> <span class="pl-ent">td</span> {</span></td>
      </tr>
      <tr>
        <td id="L381" class="blob-num js-line-number" data-line-number="381"></td>
        <td id="LC381" class="blob-code js-file-line"><span class="pl-s2">	<span class="pl-mp"><span class="pl-s3">text-align</span></span>:<span class="pl-sc">center</span>;</span></td>
      </tr>
      <tr>
        <td id="L382" class="blob-num js-line-number" data-line-number="382"></td>
        <td id="LC382" class="blob-code js-file-line"><span class="pl-s2">	<span class="pl-mp"><span class="pl-s3">padding</span></span>:<span class="pl-c1">2<span class="pl-k">px</span></span> <span class="pl-c1">3<span class="pl-k">px</span></span>;</span></td>
      </tr>
      <tr>
        <td id="L383" class="blob-num js-line-number" data-line-number="383"></td>
        <td id="LC383" class="blob-code js-file-line"><span class="pl-s2">	<span class="pl-mp"><span class="pl-s3">border</span></span>:<span class="pl-c1">1<span class="pl-k">px</span></span> <span class="pl-sc">solid</span> <span class="pl-c1">#eee</span>;</span></td>
      </tr>
      <tr>
        <td id="L384" class="blob-num js-line-number" data-line-number="384"></td>
        <td id="LC384" class="blob-code js-file-line"><span class="pl-s2">}</span></td>
      </tr>
      <tr>
        <td id="L385" class="blob-num js-line-number" data-line-number="385"></td>
        <td id="LC385" class="blob-code js-file-line"><span class="pl-s2"></span></td>
      </tr>
      <tr>
        <td id="L386" class="blob-num js-line-number" data-line-number="386"></td>
        <td id="LC386" class="blob-code js-file-line"><span class="pl-s2"><span class="pl-ent">table</span><span class="pl-e">.chart</span> <span class="pl-ent">tr</span><span class="pl-e">.enabled</span> <span class="pl-ent">td</span> {</span></td>
      </tr>
      <tr>
        <td id="L387" class="blob-num js-line-number" data-line-number="387"></td>
        <td id="LC387" class="blob-code js-file-line"><span class="pl-s2">	<span class="pl-c">/* Leave this alone */</span></span></td>
      </tr>
      <tr>
        <td id="L388" class="blob-num js-line-number" data-line-number="388"></td>
        <td id="LC388" class="blob-code js-file-line"><span class="pl-s2">}</span></td>
      </tr>
      <tr>
        <td id="L389" class="blob-num js-line-number" data-line-number="389"></td>
        <td id="LC389" class="blob-code js-file-line"><span class="pl-s2"></span></td>
      </tr>
      <tr>
        <td id="L390" class="blob-num js-line-number" data-line-number="390"></td>
        <td id="LC390" class="blob-code js-file-line"><span class="pl-s2"><span class="pl-ent">table</span><span class="pl-e">.chart</span> <span class="pl-ent">tr</span><span class="pl-e">.disabled</span> <span class="pl-ent">td</span>,</span></td>
      </tr>
      <tr>
        <td id="L391" class="blob-num js-line-number" data-line-number="391"></td>
        <td id="LC391" class="blob-code js-file-line"><span class="pl-s2"><span class="pl-ent">table</span><span class="pl-e">.chart</span> <span class="pl-ent">tr</span><span class="pl-e">.disabled</span> <span class="pl-ent">td</span> <span class="pl-ent">a</span> {</span></td>
      </tr>
      <tr>
        <td id="L392" class="blob-num js-line-number" data-line-number="392"></td>
        <td id="LC392" class="blob-code js-file-line"><span class="pl-s2">	<span class="pl-mp"><span class="pl-s3">color</span></span>:<span class="pl-c1">#999</span>;</span></td>
      </tr>
      <tr>
        <td id="L393" class="blob-num js-line-number" data-line-number="393"></td>
        <td id="LC393" class="blob-code js-file-line"><span class="pl-s2">	<span class="pl-mp"><span class="pl-s3">font-style</span></span>:<span class="pl-sc">italic</span>;</span></td>
      </tr>
      <tr>
        <td id="L394" class="blob-num js-line-number" data-line-number="394"></td>
        <td id="LC394" class="blob-code js-file-line"><span class="pl-s2">}</span></td>
      </tr>
      <tr>
        <td id="L395" class="blob-num js-line-number" data-line-number="395"></td>
        <td id="LC395" class="blob-code js-file-line"><span class="pl-s2"></span></td>
      </tr>
      <tr>
        <td id="L396" class="blob-num js-line-number" data-line-number="396"></td>
        <td id="LC396" class="blob-code js-file-line"><span class="pl-s2"><span class="pl-ent">table</span><span class="pl-e">.chart</span> <span class="pl-ent">tr</span><span class="pl-e">.disabled</span> <span class="pl-ent">td</span> <span class="pl-ent">a</span> {</span></td>
      </tr>
      <tr>
        <td id="L397" class="blob-num js-line-number" data-line-number="397"></td>
        <td id="LC397" class="blob-code js-file-line"><span class="pl-s2">	<span class="pl-mp"><span class="pl-s3">text-decoration</span></span>:<span class="pl-sc">underline</span>;</span></td>
      </tr>
      <tr>
        <td id="L398" class="blob-num js-line-number" data-line-number="398"></td>
        <td id="LC398" class="blob-code js-file-line"><span class="pl-s2">}</span></td>
      </tr>
      <tr>
        <td id="L399" class="blob-num js-line-number" data-line-number="399"></td>
        <td id="LC399" class="blob-code js-file-line"><span class="pl-s2"></span></td>
      </tr>
      <tr>
        <td id="L400" class="blob-num js-line-number" data-line-number="400"></td>
        <td id="LC400" class="blob-code js-file-line"><span class="pl-s2"><span class="pl-ent">div</span><span class="pl-e">.chunk</span> {</span></td>
      </tr>
      <tr>
        <td id="L401" class="blob-num js-line-number" data-line-number="401"></td>
        <td id="LC401" class="blob-code js-file-line"><span class="pl-s2">	<span class="pl-mp"><span class="pl-s3">margin</span></span>:<span class="pl-c1">0</span>;</span></td>
      </tr>
      <tr>
        <td id="L402" class="blob-num js-line-number" data-line-number="402"></td>
        <td id="LC402" class="blob-code js-file-line"><span class="pl-s2">	<span class="pl-mp"><span class="pl-s3">padding</span></span>:<span class="pl-c1">10<span class="pl-k">px</span></span>;</span></td>
      </tr>
      <tr>
        <td id="L403" class="blob-num js-line-number" data-line-number="403"></td>
        <td id="LC403" class="blob-code js-file-line"><span class="pl-s2">	<span class="pl-mp"><span class="pl-s3">border-bottom</span></span>:<span class="pl-c1">1<span class="pl-k">px</span></span> <span class="pl-sc">solid</span> <span class="pl-c1">#ccc</span>;</span></td>
      </tr>
      <tr>
        <td id="L404" class="blob-num js-line-number" data-line-number="404"></td>
        <td id="LC404" class="blob-code js-file-line"><span class="pl-s2">}</span></td>
      </tr>
      <tr>
        <td id="L405" class="blob-num js-line-number" data-line-number="405"></td>
        <td id="LC405" class="blob-code js-file-line"><span class="pl-s2"></span></td>
      </tr>
      <tr>
        <td id="L406" class="blob-num js-line-number" data-line-number="406"></td>
        <td id="LC406" class="blob-code js-file-line"><span class="pl-s2"><span class="pl-ent">div</span><span class="pl-e">.important</span> {</span></td>
      </tr>
      <tr>
        <td id="L407" class="blob-num js-line-number" data-line-number="407"></td>
        <td id="LC407" class="blob-code js-file-line"><span class="pl-s2">	<span class="pl-mp"><span class="pl-s3">background-color</span></span>:<span class="pl-c1">#ffc</span>;</span></td>
      </tr>
      <tr>
        <td id="L408" class="blob-num js-line-number" data-line-number="408"></td>
        <td id="LC408" class="blob-code js-file-line"><span class="pl-s2">}</span></td>
      </tr>
      <tr>
        <td id="L409" class="blob-num js-line-number" data-line-number="409"></td>
        <td id="LC409" class="blob-code js-file-line"><span class="pl-s2"></span></td>
      </tr>
      <tr>
        <td id="L410" class="blob-num js-line-number" data-line-number="410"></td>
        <td id="LC410" class="blob-code js-file-line"><span class="pl-s2"><span class="pl-ent">div</span><span class="pl-e">.ok</span> {</span></td>
      </tr>
      <tr>
        <td id="L411" class="blob-num js-line-number" data-line-number="411"></td>
        <td id="LC411" class="blob-code js-file-line"><span class="pl-s2">	<span class="pl-mp"><span class="pl-s3">background-color</span></span>:<span class="pl-c1">#cfc</span>;</span></td>
      </tr>
      <tr>
        <td id="L412" class="blob-num js-line-number" data-line-number="412"></td>
        <td id="LC412" class="blob-code js-file-line"><span class="pl-s2">}</span></td>
      </tr>
      <tr>
        <td id="L413" class="blob-num js-line-number" data-line-number="413"></td>
        <td id="LC413" class="blob-code js-file-line"><span class="pl-s2"></span></td>
      </tr>
      <tr>
        <td id="L414" class="blob-num js-line-number" data-line-number="414"></td>
        <td id="LC414" class="blob-code js-file-line"><span class="pl-s2"><span class="pl-ent">div</span><span class="pl-e">.error</span> {</span></td>
      </tr>
      <tr>
        <td id="L415" class="blob-num js-line-number" data-line-number="415"></td>
        <td id="LC415" class="blob-code js-file-line"><span class="pl-s2">	<span class="pl-mp"><span class="pl-s3">background-color</span></span>:<span class="pl-c1">#fcc</span>;</span></td>
      </tr>
      <tr>
        <td id="L416" class="blob-num js-line-number" data-line-number="416"></td>
        <td id="LC416" class="blob-code js-file-line"><span class="pl-s2">}</span></td>
      </tr>
      <tr>
        <td id="L417" class="blob-num js-line-number" data-line-number="417"></td>
        <td id="LC417" class="blob-code js-file-line"><span class="pl-s2"></span></td>
      </tr>
      <tr>
        <td id="L418" class="blob-num js-line-number" data-line-number="418"></td>
        <td id="LC418" class="blob-code js-file-line"><span class="pl-s2"><span class="pl-ent">div</span><span class="pl-e">.important</span> <span class="pl-ent">h3</span> {</span></td>
      </tr>
      <tr>
        <td id="L419" class="blob-num js-line-number" data-line-number="419"></td>
        <td id="LC419" class="blob-code js-file-line"><span class="pl-s2">	<span class="pl-mp"><span class="pl-s3">margin</span></span>: <span class="pl-c1">7<span class="pl-k">px</span></span> <span class="pl-c1">0</span> <span class="pl-c1">5<span class="pl-k">px</span></span> <span class="pl-c1">0</span>;</span></td>
      </tr>
      <tr>
        <td id="L420" class="blob-num js-line-number" data-line-number="420"></td>
        <td id="LC420" class="blob-code js-file-line"><span class="pl-s2">}</span></td>
      </tr>
      <tr>
        <td id="L421" class="blob-num js-line-number" data-line-number="421"></td>
        <td id="LC421" class="blob-code js-file-line"><span class="pl-s2"></span></td>
      </tr>
      <tr>
        <td id="L422" class="blob-num js-line-number" data-line-number="422"></td>
        <td id="LC422" class="blob-code js-file-line"><span class="pl-s2"><span class="pl-e">.footnote</span>,</span></td>
      </tr>
      <tr>
        <td id="L423" class="blob-num js-line-number" data-line-number="423"></td>
        <td id="LC423" class="blob-code js-file-line"><span class="pl-s2"><span class="pl-e">.footnote</span> <span class="pl-ent">a</span> {</span></td>
      </tr>
      <tr>
        <td id="L424" class="blob-num js-line-number" data-line-number="424"></td>
        <td id="LC424" class="blob-code js-file-line"><span class="pl-s2">	<span class="pl-mp"><span class="pl-s3">font</span></span>:<span class="pl-c1">12<span class="pl-k">px</span></span>/<span class="pl-c1">1.4<span class="pl-k">em</span></span> <span class="pl-s1"><span class="pl-pds">&quot;</span>Helvetica Neue<span class="pl-pds">&quot;</span></span>, <span class="pl-sc">Helvetica</span>, <span class="pl-s1"><span class="pl-pds">&quot;</span>Lucida Grande<span class="pl-pds">&quot;</span></span>, <span class="pl-sc">Verdana</span>, <span class="pl-sc">Arial</span>, Clean, Sans, <span class="pl-sc">sans-serif</span>;</span></td>
      </tr>
      <tr>
        <td id="L425" class="blob-num js-line-number" data-line-number="425"></td>
        <td id="LC425" class="blob-code js-file-line"><span class="pl-s2">	<span class="pl-mp"><span class="pl-s3">color</span></span>:<span class="pl-c1">#aaa</span>;</span></td>
      </tr>
      <tr>
        <td id="L426" class="blob-num js-line-number" data-line-number="426"></td>
        <td id="LC426" class="blob-code js-file-line"><span class="pl-s2">}</span></td>
      </tr>
      <tr>
        <td id="L427" class="blob-num js-line-number" data-line-number="427"></td>
        <td id="LC427" class="blob-code js-file-line"><span class="pl-s2"></span></td>
      </tr>
      <tr>
        <td id="L428" class="blob-num js-line-number" data-line-number="428"></td>
        <td id="LC428" class="blob-code js-file-line"><span class="pl-s2"><span class="pl-e">.footnote</span> <span class="pl-ent">em</span> {</span></td>
      </tr>
      <tr>
        <td id="L429" class="blob-num js-line-number" data-line-number="429"></td>
        <td id="LC429" class="blob-code js-file-line"><span class="pl-s2">	<span class="pl-mp"><span class="pl-s3">background-color</span></span>:<span class="pl-sc">transparent</span>;</span></td>
      </tr>
      <tr>
        <td id="L430" class="blob-num js-line-number" data-line-number="430"></td>
        <td id="LC430" class="blob-code js-file-line"><span class="pl-s2">	<span class="pl-mp"><span class="pl-s3">font-style</span></span>:<span class="pl-sc">italic</span>;</span></td>
      </tr>
      <tr>
        <td id="L431" class="blob-num js-line-number" data-line-number="431"></td>
        <td id="LC431" class="blob-code js-file-line"><span class="pl-s2">}</span></td>
      </tr>
      <tr>
        <td id="L432" class="blob-num js-line-number" data-line-number="432"></td>
        <td id="LC432" class="blob-code js-file-line"><span class="pl-s2">&lt;/<span class="pl-ent">style</span>&gt;</span></td>
      </tr>
      <tr>
        <td id="L433" class="blob-num js-line-number" data-line-number="433"></td>
        <td id="LC433" class="blob-code js-file-line">
</td>
      </tr>
      <tr>
        <td id="L434" class="blob-num js-line-number" data-line-number="434"></td>
        <td id="LC434" class="blob-code js-file-line">&lt;/<span class="pl-ent">head</span>&gt;</td>
      </tr>
      <tr>
        <td id="L435" class="blob-num js-line-number" data-line-number="435"></td>
        <td id="LC435" class="blob-code js-file-line">
</td>
      </tr>
      <tr>
        <td id="L436" class="blob-num js-line-number" data-line-number="436"></td>
        <td id="LC436" class="blob-code js-file-line">&lt;<span class="pl-ent">body</span>&gt;</td>
      </tr>
      <tr>
        <td id="L437" class="blob-num js-line-number" data-line-number="437"></td>
        <td id="LC437" class="blob-code js-file-line">
</td>
      </tr>
      <tr>
        <td id="L438" class="blob-num js-line-number" data-line-number="438"></td>
        <td id="LC438" class="blob-code js-file-line">&lt;<span class="pl-ent">div</span> <span class="pl-e">id</span>=<span class="pl-s1"><span class="pl-pds">&quot;</span>site<span class="pl-pds">&quot;</span></span>&gt;</td>
      </tr>
      <tr>
        <td id="L439" class="blob-num js-line-number" data-line-number="439"></td>
        <td id="LC439" class="blob-code js-file-line">	&lt;<span class="pl-ent">div</span> <span class="pl-e">id</span>=<span class="pl-s1"><span class="pl-pds">&quot;</span>content<span class="pl-pds">&quot;</span></span>&gt;</td>
      </tr>
      <tr>
        <td id="L440" class="blob-num js-line-number" data-line-number="440"></td>
        <td id="LC440" class="blob-code js-file-line">
</td>
      </tr>
      <tr>
        <td id="L441" class="blob-num js-line-number" data-line-number="441"></td>
        <td id="LC441" class="blob-code js-file-line">		&lt;<span class="pl-ent">div</span> <span class="pl-e">class</span>=<span class="pl-s1"><span class="pl-pds">&quot;</span>chunk<span class="pl-pds">&quot;</span></span>&gt;</td>
      </tr>
      <tr>
        <td id="L442" class="blob-num js-line-number" data-line-number="442"></td>
        <td id="LC442" class="blob-code js-file-line">			&lt;<span class="pl-ent">h2</span> <span class="pl-e">style</span>=<span class="pl-s1"><span class="pl-pds">&quot;</span>text-align:center;<span class="pl-pds">&quot;</span></span>&gt;&lt;<span class="pl-ent">img</span> <span class="pl-e">src</span>=<span class="pl-s1"><span class="pl-pds">&quot;</span><span class="pl-pse">&lt;?php</span><span class="pl-s2"> <span class="pl-s3">echo</span> <span class="pl-s3">pathinfo</span>(<span class="pl-c1">__FILE__</span>, <span class="pl-sc">PATHINFO_BASENAME</span>); </span><span class="pl-pse"><span class="pl-s2">?</span>&gt;</span>?logopng<span class="pl-pds">&quot;</span></span> <span class="pl-e">alt</span>=<span class="pl-s1"><span class="pl-pds">&quot;</span>SDK Compatibility Test<span class="pl-pds">&quot;</span></span> <span class="pl-e">title</span>=<span class="pl-s1"><span class="pl-pds">&quot;</span>SDK Compatibility Test<span class="pl-pds">&quot;</span></span> /&gt;&lt;/<span class="pl-ent">h2</span>&gt;</td>
      </tr>
      <tr>
        <td id="L443" class="blob-num js-line-number" data-line-number="443"></td>
        <td id="LC443" class="blob-code js-file-line">
</td>
      </tr>
      <tr>
        <td id="L444" class="blob-num js-line-number" data-line-number="444"></td>
        <td id="LC444" class="blob-code js-file-line">			&lt;<span class="pl-ent">h3</span>&gt;Minimum Requirements&lt;/<span class="pl-ent">h3</span>&gt;</td>
      </tr>
      <tr>
        <td id="L445" class="blob-num js-line-number" data-line-number="445"></td>
        <td id="LC445" class="blob-code js-file-line">			&lt;<span class="pl-ent">table</span> <span class="pl-e">cellpadding</span>=<span class="pl-s1"><span class="pl-pds">&quot;</span>0<span class="pl-pds">&quot;</span></span> <span class="pl-e">cellspacing</span>=<span class="pl-s1"><span class="pl-pds">&quot;</span>0<span class="pl-pds">&quot;</span></span> <span class="pl-e">border</span>=<span class="pl-s1"><span class="pl-pds">&quot;</span>0<span class="pl-pds">&quot;</span></span> <span class="pl-e">width</span>=<span class="pl-s1"><span class="pl-pds">&quot;</span>100%<span class="pl-pds">&quot;</span></span> <span class="pl-e">class</span>=<span class="pl-s1"><span class="pl-pds">&quot;</span>chart<span class="pl-pds">&quot;</span></span>&gt;</td>
      </tr>
      <tr>
        <td id="L446" class="blob-num js-line-number" data-line-number="446"></td>
        <td id="LC446" class="blob-code js-file-line">				&lt;<span class="pl-ent">thead</span>&gt;</td>
      </tr>
      <tr>
        <td id="L447" class="blob-num js-line-number" data-line-number="447"></td>
        <td id="LC447" class="blob-code js-file-line">					&lt;<span class="pl-ent">tr</span>&gt;</td>
      </tr>
      <tr>
        <td id="L448" class="blob-num js-line-number" data-line-number="448"></td>
        <td id="LC448" class="blob-code js-file-line">						&lt;<span class="pl-ent">th</span>&gt;Test&lt;/<span class="pl-ent">th</span>&gt;</td>
      </tr>
      <tr>
        <td id="L449" class="blob-num js-line-number" data-line-number="449"></td>
        <td id="LC449" class="blob-code js-file-line">						&lt;<span class="pl-ent">th</span>&gt;Should Be&lt;/<span class="pl-ent">th</span>&gt;</td>
      </tr>
      <tr>
        <td id="L450" class="blob-num js-line-number" data-line-number="450"></td>
        <td id="LC450" class="blob-code js-file-line">						&lt;<span class="pl-ent">th</span>&gt;What You Have&lt;/<span class="pl-ent">th</span>&gt;</td>
      </tr>
      <tr>
        <td id="L451" class="blob-num js-line-number" data-line-number="451"></td>
        <td id="LC451" class="blob-code js-file-line">					&lt;/<span class="pl-ent">tr</span>&gt;</td>
      </tr>
      <tr>
        <td id="L452" class="blob-num js-line-number" data-line-number="452"></td>
        <td id="LC452" class="blob-code js-file-line">				&lt;/<span class="pl-ent">thead</span>&gt;</td>
      </tr>
      <tr>
        <td id="L453" class="blob-num js-line-number" data-line-number="453"></td>
        <td id="LC453" class="blob-code js-file-line">				&lt;<span class="pl-ent">tbody</span>&gt;</td>
      </tr>
      <tr>
        <td id="L454" class="blob-num js-line-number" data-line-number="454"></td>
        <td id="LC454" class="blob-code js-file-line">					&lt;<span class="pl-ent">tr</span> <span class="pl-e">class</span>=<span class="pl-s1"><span class="pl-pds">&quot;</span><span class="pl-pse">&lt;?php</span><span class="pl-s2"> <span class="pl-s3">echo</span> (<span class="pl-vo">$php_ok</span>) ? <span class="pl-s1"><span class="pl-pds">&#39;</span>enabled<span class="pl-pds">&#39;</span></span> : <span class="pl-s1"><span class="pl-pds">&#39;</span>disabled<span class="pl-pds">&#39;</span></span>; </span><span class="pl-pse"><span class="pl-s2">?</span>&gt;</span><span class="pl-pds">&quot;</span></span>&gt;</td>
      </tr>
      <tr>
        <td id="L455" class="blob-num js-line-number" data-line-number="455"></td>
        <td id="LC455" class="blob-code js-file-line">						&lt;<span class="pl-ent">td</span>&gt;PHP&lt;/<span class="pl-ent">td</span>&gt;</td>
      </tr>
      <tr>
        <td id="L456" class="blob-num js-line-number" data-line-number="456"></td>
        <td id="LC456" class="blob-code js-file-line">						&lt;<span class="pl-ent">td</span>&gt;5.2 or newer&lt;/<span class="pl-ent">td</span>&gt;</td>
      </tr>
      <tr>
        <td id="L457" class="blob-num js-line-number" data-line-number="457"></td>
        <td id="LC457" class="blob-code js-file-line">						&lt;<span class="pl-ent">td</span>&gt;<span class="pl-pse">&lt;?php</span><span class="pl-s2"> <span class="pl-s3">echo</span> <span class="pl-s3">phpversion</span>(); </span><span class="pl-pse"><span class="pl-s2">?</span>&gt;</span>&lt;/<span class="pl-ent">td</span>&gt;</td>
      </tr>
      <tr>
        <td id="L458" class="blob-num js-line-number" data-line-number="458"></td>
        <td id="LC458" class="blob-code js-file-line">					&lt;/<span class="pl-ent">tr</span>&gt;</td>
      </tr>
      <tr>
        <td id="L459" class="blob-num js-line-number" data-line-number="459"></td>
        <td id="LC459" class="blob-code js-file-line">					&lt;<span class="pl-ent">tr</span> <span class="pl-e">class</span>=<span class="pl-s1"><span class="pl-pds">&quot;</span><span class="pl-pse">&lt;?php</span><span class="pl-s2"> <span class="pl-s3">echo</span> (<span class="pl-vo">$curl_ok</span>) ? <span class="pl-s1"><span class="pl-pds">&#39;</span>enabled<span class="pl-pds">&#39;</span></span> : <span class="pl-s1"><span class="pl-pds">&#39;</span>disabled<span class="pl-pds">&#39;</span></span>; </span><span class="pl-pse"><span class="pl-s2">?</span>&gt;</span><span class="pl-pds">&quot;</span></span>&gt;</td>
      </tr>
      <tr>
        <td id="L460" class="blob-num js-line-number" data-line-number="460"></td>
        <td id="LC460" class="blob-code js-file-line">						&lt;<span class="pl-ent">td</span>&gt;&lt;<span class="pl-ent">a</span> <span class="pl-e">href</span>=<span class="pl-s1"><span class="pl-pds">&quot;</span>http://php.net/curl<span class="pl-pds">&quot;</span></span>&gt;cURL&lt;/<span class="pl-ent">a</span>&gt;&lt;/<span class="pl-ent">td</span>&gt;</td>
      </tr>
      <tr>
        <td id="L461" class="blob-num js-line-number" data-line-number="461"></td>
        <td id="LC461" class="blob-code js-file-line">						&lt;<span class="pl-ent">td</span>&gt;7.15.0 or newer, with SSL&lt;/<span class="pl-ent">td</span>&gt;</td>
      </tr>
      <tr>
        <td id="L462" class="blob-num js-line-number" data-line-number="462"></td>
        <td id="LC462" class="blob-code js-file-line">						&lt;<span class="pl-ent">td</span>&gt;<span class="pl-pse">&lt;?php</span><span class="pl-s2"> <span class="pl-s3">echo</span> (<span class="pl-vo">$curl_ok</span>) ? (<span class="pl-vo">$curl_version</span>[<span class="pl-s1"><span class="pl-pds">&#39;</span>version<span class="pl-pds">&#39;</span></span>] <span class="pl-k">.</span> <span class="pl-s1"><span class="pl-pds">&#39;</span> (<span class="pl-pds">&#39;</span></span> <span class="pl-k">.</span> <span class="pl-vo">$curl_version</span>[<span class="pl-s1"><span class="pl-pds">&#39;</span>ssl_version<span class="pl-pds">&#39;</span></span>] <span class="pl-k">.</span> <span class="pl-s1"><span class="pl-pds">&#39;</span>)<span class="pl-pds">&#39;</span></span>) : (<span class="pl-vo">$curl_version</span>[<span class="pl-s1"><span class="pl-pds">&#39;</span>version<span class="pl-pds">&#39;</span></span>] <span class="pl-k">.</span> (<span class="pl-s3">in_array</span>(<span class="pl-s1"><span class="pl-pds">&#39;</span>https<span class="pl-pds">&#39;</span></span>, <span class="pl-vo">$curl_version</span>[<span class="pl-s1"><span class="pl-pds">&#39;</span>protocols<span class="pl-pds">&#39;</span></span>], <span class="pl-c1">true</span>) ? <span class="pl-s1"><span class="pl-pds">&#39;</span> (with <span class="pl-pds">&#39;</span></span> <span class="pl-k">.</span> <span class="pl-vo">$curl_version</span>[<span class="pl-s1"><span class="pl-pds">&#39;</span>ssl_version<span class="pl-pds">&#39;</span></span>] <span class="pl-k">.</span> <span class="pl-s1"><span class="pl-pds">&#39;</span>)<span class="pl-pds">&#39;</span></span> : <span class="pl-s1"><span class="pl-pds">&#39;</span> (without SSL)<span class="pl-pds">&#39;</span></span>)); </span><span class="pl-pse"><span class="pl-s2">?</span>&gt;</span>&lt;/<span class="pl-ent">td</span>&gt;</td>
      </tr>
      <tr>
        <td id="L463" class="blob-num js-line-number" data-line-number="463"></td>
        <td id="LC463" class="blob-code js-file-line">					&lt;/<span class="pl-ent">tr</span>&gt;</td>
      </tr>
      <tr>
        <td id="L464" class="blob-num js-line-number" data-line-number="464"></td>
        <td id="LC464" class="blob-code js-file-line">					&lt;<span class="pl-ent">tr</span> <span class="pl-e">class</span>=<span class="pl-s1"><span class="pl-pds">&quot;</span><span class="pl-pse">&lt;?php</span><span class="pl-s2"> <span class="pl-s3">echo</span> (<span class="pl-vo">$simplexml_ok</span>) ? <span class="pl-s1"><span class="pl-pds">&#39;</span>enabled<span class="pl-pds">&#39;</span></span> : <span class="pl-s1"><span class="pl-pds">&#39;</span>disabled<span class="pl-pds">&#39;</span></span>; </span><span class="pl-pse"><span class="pl-s2">?</span>&gt;</span><span class="pl-pds">&quot;</span></span>&gt;</td>
      </tr>
      <tr>
        <td id="L465" class="blob-num js-line-number" data-line-number="465"></td>
        <td id="LC465" class="blob-code js-file-line">						&lt;<span class="pl-ent">td</span>&gt;&lt;<span class="pl-ent">a</span> <span class="pl-e">href</span>=<span class="pl-s1"><span class="pl-pds">&quot;</span>http://php.net/simplexml<span class="pl-pds">&quot;</span></span>&gt;SimpleXML&lt;/<span class="pl-ent">a</span>&gt;&lt;/<span class="pl-ent">td</span>&gt;</td>
      </tr>
      <tr>
        <td id="L466" class="blob-num js-line-number" data-line-number="466"></td>
        <td id="LC466" class="blob-code js-file-line">						&lt;<span class="pl-ent">td</span>&gt;Enabled&lt;/<span class="pl-ent">td</span>&gt;</td>
      </tr>
      <tr>
        <td id="L467" class="blob-num js-line-number" data-line-number="467"></td>
        <td id="LC467" class="blob-code js-file-line">						&lt;<span class="pl-ent">td</span>&gt;<span class="pl-pse">&lt;?php</span><span class="pl-s2"> <span class="pl-s3">echo</span> (<span class="pl-vo">$simplexml_ok</span>) ? <span class="pl-s1"><span class="pl-pds">&#39;</span>Enabled<span class="pl-pds">&#39;</span></span> : <span class="pl-s1"><span class="pl-pds">&#39;</span>Disabled<span class="pl-pds">&#39;</span></span>; </span><span class="pl-pse"><span class="pl-s2">?</span>&gt;</span>&lt;/<span class="pl-ent">td</span>&gt;</td>
      </tr>
      <tr>
        <td id="L468" class="blob-num js-line-number" data-line-number="468"></td>
        <td id="LC468" class="blob-code js-file-line">					&lt;/<span class="pl-ent">tr</span>&gt;</td>
      </tr>
      <tr>
        <td id="L469" class="blob-num js-line-number" data-line-number="469"></td>
        <td id="LC469" class="blob-code js-file-line">					&lt;<span class="pl-ent">tr</span> <span class="pl-e">class</span>=<span class="pl-s1"><span class="pl-pds">&quot;</span><span class="pl-pse">&lt;?php</span><span class="pl-s2"> <span class="pl-s3">echo</span> (<span class="pl-vo">$dom_ok</span>) ? <span class="pl-s1"><span class="pl-pds">&#39;</span>enabled<span class="pl-pds">&#39;</span></span> : <span class="pl-s1"><span class="pl-pds">&#39;</span>disabled<span class="pl-pds">&#39;</span></span>; </span><span class="pl-pse"><span class="pl-s2">?</span>&gt;</span><span class="pl-pds">&quot;</span></span>&gt;</td>
      </tr>
      <tr>
        <td id="L470" class="blob-num js-line-number" data-line-number="470"></td>
        <td id="LC470" class="blob-code js-file-line">						&lt;<span class="pl-ent">td</span>&gt;&lt;<span class="pl-ent">a</span> <span class="pl-e">href</span>=<span class="pl-s1"><span class="pl-pds">&quot;</span>http://php.net/dom<span class="pl-pds">&quot;</span></span>&gt;DOM&lt;/<span class="pl-ent">a</span>&gt;&lt;/<span class="pl-ent">td</span>&gt;</td>
      </tr>
      <tr>
        <td id="L471" class="blob-num js-line-number" data-line-number="471"></td>
        <td id="LC471" class="blob-code js-file-line">						&lt;<span class="pl-ent">td</span>&gt;Enabled&lt;/<span class="pl-ent">td</span>&gt;</td>
      </tr>
      <tr>
        <td id="L472" class="blob-num js-line-number" data-line-number="472"></td>
        <td id="LC472" class="blob-code js-file-line">						&lt;<span class="pl-ent">td</span>&gt;<span class="pl-pse">&lt;?php</span><span class="pl-s2"> <span class="pl-s3">echo</span> (<span class="pl-vo">$dom_ok</span>) ? <span class="pl-s1"><span class="pl-pds">&#39;</span>Enabled<span class="pl-pds">&#39;</span></span> : <span class="pl-s1"><span class="pl-pds">&#39;</span>Disabled<span class="pl-pds">&#39;</span></span>; </span><span class="pl-pse"><span class="pl-s2">?</span>&gt;</span>&lt;/<span class="pl-ent">td</span>&gt;</td>
      </tr>
      <tr>
        <td id="L473" class="blob-num js-line-number" data-line-number="473"></td>
        <td id="LC473" class="blob-code js-file-line">					&lt;/<span class="pl-ent">tr</span>&gt;</td>
      </tr>
      <tr>
        <td id="L474" class="blob-num js-line-number" data-line-number="474"></td>
        <td id="LC474" class="blob-code js-file-line">					&lt;<span class="pl-ent">tr</span> <span class="pl-e">class</span>=<span class="pl-s1"><span class="pl-pds">&quot;</span><span class="pl-pse">&lt;?php</span><span class="pl-s2"> <span class="pl-s3">echo</span> (<span class="pl-vo">$spl_ok</span>) ? <span class="pl-s1"><span class="pl-pds">&#39;</span>enabled<span class="pl-pds">&#39;</span></span> : <span class="pl-s1"><span class="pl-pds">&#39;</span>disabled<span class="pl-pds">&#39;</span></span>; </span><span class="pl-pse"><span class="pl-s2">?</span>&gt;</span><span class="pl-pds">&quot;</span></span>&gt;</td>
      </tr>
      <tr>
        <td id="L475" class="blob-num js-line-number" data-line-number="475"></td>
        <td id="LC475" class="blob-code js-file-line">						&lt;<span class="pl-ent">td</span>&gt;&lt;<span class="pl-ent">a</span> <span class="pl-e">href</span>=<span class="pl-s1"><span class="pl-pds">&quot;</span>http://php.net/spl<span class="pl-pds">&quot;</span></span>&gt;SPL&lt;/<span class="pl-ent">a</span>&gt;&lt;/<span class="pl-ent">td</span>&gt;</td>
      </tr>
      <tr>
        <td id="L476" class="blob-num js-line-number" data-line-number="476"></td>
        <td id="LC476" class="blob-code js-file-line">						&lt;<span class="pl-ent">td</span>&gt;Enabled&lt;/<span class="pl-ent">td</span>&gt;</td>
      </tr>
      <tr>
        <td id="L477" class="blob-num js-line-number" data-line-number="477"></td>
        <td id="LC477" class="blob-code js-file-line">						&lt;<span class="pl-ent">td</span>&gt;<span class="pl-pse">&lt;?php</span><span class="pl-s2"> <span class="pl-s3">echo</span> (<span class="pl-vo">$spl_ok</span>) ? <span class="pl-s1"><span class="pl-pds">&#39;</span>Enabled<span class="pl-pds">&#39;</span></span> : <span class="pl-s1"><span class="pl-pds">&#39;</span>Disabled<span class="pl-pds">&#39;</span></span>; </span><span class="pl-pse"><span class="pl-s2">?</span>&gt;</span>&lt;/<span class="pl-ent">td</span>&gt;</td>
      </tr>
      <tr>
        <td id="L478" class="blob-num js-line-number" data-line-number="478"></td>
        <td id="LC478" class="blob-code js-file-line">					&lt;/<span class="pl-ent">tr</span>&gt;</td>
      </tr>
      <tr>
        <td id="L479" class="blob-num js-line-number" data-line-number="479"></td>
        <td id="LC479" class="blob-code js-file-line">					&lt;<span class="pl-ent">tr</span> <span class="pl-e">class</span>=<span class="pl-s1"><span class="pl-pds">&quot;</span><span class="pl-pse">&lt;?php</span><span class="pl-s2"> <span class="pl-s3">echo</span> (<span class="pl-vo">$json_ok</span>) ? <span class="pl-s1"><span class="pl-pds">&#39;</span>enabled<span class="pl-pds">&#39;</span></span> : <span class="pl-s1"><span class="pl-pds">&#39;</span>disabled<span class="pl-pds">&#39;</span></span>; </span><span class="pl-pse"><span class="pl-s2">?</span>&gt;</span><span class="pl-pds">&quot;</span></span>&gt;</td>
      </tr>
      <tr>
        <td id="L480" class="blob-num js-line-number" data-line-number="480"></td>
        <td id="LC480" class="blob-code js-file-line">						&lt;<span class="pl-ent">td</span>&gt;&lt;<span class="pl-ent">a</span> <span class="pl-e">href</span>=<span class="pl-s1"><span class="pl-pds">&quot;</span>http://php.net/json<span class="pl-pds">&quot;</span></span>&gt;JSON&lt;/<span class="pl-ent">a</span>&gt;&lt;/<span class="pl-ent">td</span>&gt;</td>
      </tr>
      <tr>
        <td id="L481" class="blob-num js-line-number" data-line-number="481"></td>
        <td id="LC481" class="blob-code js-file-line">						&lt;<span class="pl-ent">td</span>&gt;Enabled&lt;/<span class="pl-ent">td</span>&gt;</td>
      </tr>
      <tr>
        <td id="L482" class="blob-num js-line-number" data-line-number="482"></td>
        <td id="LC482" class="blob-code js-file-line">						&lt;<span class="pl-ent">td</span>&gt;<span class="pl-pse">&lt;?php</span><span class="pl-s2"> <span class="pl-s3">echo</span> (<span class="pl-vo">$json_ok</span>) ? <span class="pl-s1"><span class="pl-pds">&#39;</span>Enabled<span class="pl-pds">&#39;</span></span> : <span class="pl-s1"><span class="pl-pds">&#39;</span>Disabled<span class="pl-pds">&#39;</span></span>; </span><span class="pl-pse"><span class="pl-s2">?</span>&gt;</span>&lt;/<span class="pl-ent">td</span>&gt;</td>
      </tr>
      <tr>
        <td id="L483" class="blob-num js-line-number" data-line-number="483"></td>
        <td id="LC483" class="blob-code js-file-line">					&lt;/<span class="pl-ent">tr</span>&gt;</td>
      </tr>
      <tr>
        <td id="L484" class="blob-num js-line-number" data-line-number="484"></td>
        <td id="LC484" class="blob-code js-file-line">					&lt;<span class="pl-ent">tr</span> <span class="pl-e">class</span>=<span class="pl-s1"><span class="pl-pds">&quot;</span><span class="pl-pse">&lt;?php</span><span class="pl-s2"> <span class="pl-s3">echo</span> (<span class="pl-vo">$pcre_ok</span>) ? <span class="pl-s1"><span class="pl-pds">&#39;</span>enabled<span class="pl-pds">&#39;</span></span> : <span class="pl-s1"><span class="pl-pds">&#39;</span>disabled<span class="pl-pds">&#39;</span></span>; </span><span class="pl-pse"><span class="pl-s2">?</span>&gt;</span><span class="pl-pds">&quot;</span></span>&gt;</td>
      </tr>
      <tr>
        <td id="L485" class="blob-num js-line-number" data-line-number="485"></td>
        <td id="LC485" class="blob-code js-file-line">						&lt;<span class="pl-ent">td</span>&gt;&lt;<span class="pl-ent">a</span> <span class="pl-e">href</span>=<span class="pl-s1"><span class="pl-pds">&quot;</span>http://php.net/pcre<span class="pl-pds">&quot;</span></span>&gt;PCRE&lt;/<span class="pl-ent">a</span>&gt;&lt;/<span class="pl-ent">td</span>&gt;</td>
      </tr>
      <tr>
        <td id="L486" class="blob-num js-line-number" data-line-number="486"></td>
        <td id="LC486" class="blob-code js-file-line">						&lt;<span class="pl-ent">td</span>&gt;Enabled&lt;/<span class="pl-ent">td</span>&gt;</td>
      </tr>
      <tr>
        <td id="L487" class="blob-num js-line-number" data-line-number="487"></td>
        <td id="LC487" class="blob-code js-file-line">						&lt;<span class="pl-ent">td</span>&gt;<span class="pl-pse">&lt;?php</span><span class="pl-s2"> <span class="pl-s3">echo</span> (<span class="pl-vo">$pcre_ok</span>) ? <span class="pl-s1"><span class="pl-pds">&#39;</span>Enabled<span class="pl-pds">&#39;</span></span> : <span class="pl-s1"><span class="pl-pds">&#39;</span>Disabled<span class="pl-pds">&#39;</span></span>; </span><span class="pl-pse"><span class="pl-s2">?</span>&gt;</span>&lt;/<span class="pl-ent">td</span>&gt;</td>
      </tr>
      <tr>
        <td id="L488" class="blob-num js-line-number" data-line-number="488"></td>
        <td id="LC488" class="blob-code js-file-line">					&lt;/<span class="pl-ent">tr</span>&gt;</td>
      </tr>
      <tr>
        <td id="L489" class="blob-num js-line-number" data-line-number="489"></td>
        <td id="LC489" class="blob-code js-file-line">					&lt;<span class="pl-ent">tr</span> <span class="pl-e">class</span>=<span class="pl-s1"><span class="pl-pds">&quot;</span><span class="pl-pse">&lt;?php</span><span class="pl-s2"> <span class="pl-s3">echo</span> (<span class="pl-vo">$file_ok</span>) ? <span class="pl-s1"><span class="pl-pds">&#39;</span>enabled<span class="pl-pds">&#39;</span></span> : <span class="pl-s1"><span class="pl-pds">&#39;</span>disabled<span class="pl-pds">&#39;</span></span>; </span><span class="pl-pse"><span class="pl-s2">?</span>&gt;</span><span class="pl-pds">&quot;</span></span>&gt;</td>
      </tr>
      <tr>
        <td id="L490" class="blob-num js-line-number" data-line-number="490"></td>
        <td id="LC490" class="blob-code js-file-line">						&lt;<span class="pl-ent">td</span>&gt;File System &lt;<span class="pl-ent">a</span> <span class="pl-e">href</span>=<span class="pl-s1"><span class="pl-pds">&quot;</span>http://php.net/file_get_contents<span class="pl-pds">&quot;</span></span>&gt;Read&lt;/<span class="pl-ent">a</span>&gt;/&lt;<span class="pl-ent">a</span> <span class="pl-e">href</span>=<span class="pl-s1"><span class="pl-pds">&quot;</span>http://php.net/file_put_contents<span class="pl-pds">&quot;</span></span>&gt;Write&lt;/<span class="pl-ent">a</span>&gt;&lt;/<span class="pl-ent">td</span>&gt;</td>
      </tr>
      <tr>
        <td id="L491" class="blob-num js-line-number" data-line-number="491"></td>
        <td id="LC491" class="blob-code js-file-line">						&lt;<span class="pl-ent">td</span>&gt;Enabled&lt;/<span class="pl-ent">td</span>&gt;</td>
      </tr>
      <tr>
        <td id="L492" class="blob-num js-line-number" data-line-number="492"></td>
        <td id="LC492" class="blob-code js-file-line">						&lt;<span class="pl-ent">td</span>&gt;<span class="pl-pse">&lt;?php</span><span class="pl-s2"> <span class="pl-s3">echo</span> (<span class="pl-vo">$file_ok</span>) ? <span class="pl-s1"><span class="pl-pds">&#39;</span>Enabled<span class="pl-pds">&#39;</span></span> : <span class="pl-s1"><span class="pl-pds">&#39;</span>Disabled<span class="pl-pds">&#39;</span></span>; </span><span class="pl-pse"><span class="pl-s2">?</span>&gt;</span>&lt;/<span class="pl-ent">td</span>&gt;</td>
      </tr>
      <tr>
        <td id="L493" class="blob-num js-line-number" data-line-number="493"></td>
        <td id="LC493" class="blob-code js-file-line">					&lt;/<span class="pl-ent">tr</span>&gt;</td>
      </tr>
      <tr>
        <td id="L494" class="blob-num js-line-number" data-line-number="494"></td>
        <td id="LC494" class="blob-code js-file-line">				&lt;/<span class="pl-ent">tbody</span>&gt;</td>
      </tr>
      <tr>
        <td id="L495" class="blob-num js-line-number" data-line-number="495"></td>
        <td id="LC495" class="blob-code js-file-line">			&lt;/<span class="pl-ent">table</span>&gt;</td>
      </tr>
      <tr>
        <td id="L496" class="blob-num js-line-number" data-line-number="496"></td>
        <td id="LC496" class="blob-code js-file-line">
</td>
      </tr>
      <tr>
        <td id="L497" class="blob-num js-line-number" data-line-number="497"></td>
        <td id="LC497" class="blob-code js-file-line">			&lt;<span class="pl-ent">h3</span>&gt;Optional Extensions&lt;/<span class="pl-ent">h3</span>&gt;</td>
      </tr>
      <tr>
        <td id="L498" class="blob-num js-line-number" data-line-number="498"></td>
        <td id="LC498" class="blob-code js-file-line">			&lt;<span class="pl-ent">table</span> <span class="pl-e">cellpadding</span>=<span class="pl-s1"><span class="pl-pds">&quot;</span>0<span class="pl-pds">&quot;</span></span> <span class="pl-e">cellspacing</span>=<span class="pl-s1"><span class="pl-pds">&quot;</span>0<span class="pl-pds">&quot;</span></span> <span class="pl-e">border</span>=<span class="pl-s1"><span class="pl-pds">&quot;</span>0<span class="pl-pds">&quot;</span></span> <span class="pl-e">width</span>=<span class="pl-s1"><span class="pl-pds">&quot;</span>100%<span class="pl-pds">&quot;</span></span> <span class="pl-e">class</span>=<span class="pl-s1"><span class="pl-pds">&quot;</span>chart<span class="pl-pds">&quot;</span></span>&gt;</td>
      </tr>
      <tr>
        <td id="L499" class="blob-num js-line-number" data-line-number="499"></td>
        <td id="LC499" class="blob-code js-file-line">				&lt;<span class="pl-ent">thead</span>&gt;</td>
      </tr>
      <tr>
        <td id="L500" class="blob-num js-line-number" data-line-number="500"></td>
        <td id="LC500" class="blob-code js-file-line">					&lt;<span class="pl-ent">tr</span>&gt;</td>
      </tr>
      <tr>
        <td id="L501" class="blob-num js-line-number" data-line-number="501"></td>
        <td id="LC501" class="blob-code js-file-line">						&lt;<span class="pl-ent">th</span>&gt;Test&lt;/<span class="pl-ent">th</span>&gt;</td>
      </tr>
      <tr>
        <td id="L502" class="blob-num js-line-number" data-line-number="502"></td>
        <td id="LC502" class="blob-code js-file-line">						&lt;<span class="pl-ent">th</span>&gt;Would Like To Be&lt;/<span class="pl-ent">th</span>&gt;</td>
      </tr>
      <tr>
        <td id="L503" class="blob-num js-line-number" data-line-number="503"></td>
        <td id="LC503" class="blob-code js-file-line">						&lt;<span class="pl-ent">th</span>&gt;What You Have&lt;/<span class="pl-ent">th</span>&gt;</td>
      </tr>
      <tr>
        <td id="L504" class="blob-num js-line-number" data-line-number="504"></td>
        <td id="LC504" class="blob-code js-file-line">					&lt;/<span class="pl-ent">tr</span>&gt;</td>
      </tr>
      <tr>
        <td id="L505" class="blob-num js-line-number" data-line-number="505"></td>
        <td id="LC505" class="blob-code js-file-line">				&lt;/<span class="pl-ent">thead</span>&gt;</td>
      </tr>
      <tr>
        <td id="L506" class="blob-num js-line-number" data-line-number="506"></td>
        <td id="LC506" class="blob-code js-file-line">				&lt;<span class="pl-ent">tbody</span>&gt;</td>
      </tr>
      <tr>
        <td id="L507" class="blob-num js-line-number" data-line-number="507"></td>
        <td id="LC507" class="blob-code js-file-line">					&lt;<span class="pl-ent">tr</span> <span class="pl-e">class</span>=<span class="pl-s1"><span class="pl-pds">&quot;</span><span class="pl-pse">&lt;?php</span><span class="pl-s2"> <span class="pl-s3">echo</span> (<span class="pl-vo">$openssl_ok</span>) ? <span class="pl-s1"><span class="pl-pds">&#39;</span>enabled<span class="pl-pds">&#39;</span></span> : <span class="pl-s1"><span class="pl-pds">&#39;</span>disabled<span class="pl-pds">&#39;</span></span>; </span><span class="pl-pse"><span class="pl-s2">?</span>&gt;</span><span class="pl-pds">&quot;</span></span>&gt;</td>
      </tr>
      <tr>
        <td id="L508" class="blob-num js-line-number" data-line-number="508"></td>
        <td id="LC508" class="blob-code js-file-line">						&lt;<span class="pl-ent">td</span>&gt;&lt;<span class="pl-ent">a</span> <span class="pl-e">href</span>=<span class="pl-s1"><span class="pl-pds">&quot;</span>http://php.net/openssl<span class="pl-pds">&quot;</span></span>&gt;OpenSSL&lt;/<span class="pl-ent">a</span>&gt;&lt;/<span class="pl-ent">td</span>&gt;</td>
      </tr>
      <tr>
        <td id="L509" class="blob-num js-line-number" data-line-number="509"></td>
        <td id="LC509" class="blob-code js-file-line">						&lt;<span class="pl-ent">td</span>&gt;Enabled&lt;/<span class="pl-ent">td</span>&gt;</td>
      </tr>
      <tr>
        <td id="L510" class="blob-num js-line-number" data-line-number="510"></td>
        <td id="LC510" class="blob-code js-file-line">						&lt;<span class="pl-ent">td</span>&gt;<span class="pl-pse">&lt;?php</span><span class="pl-s2"> <span class="pl-s3">echo</span> (<span class="pl-vo">$openssl_ok</span>) ? <span class="pl-s1"><span class="pl-pds">&#39;</span>Enabled<span class="pl-pds">&#39;</span></span> : <span class="pl-s1"><span class="pl-pds">&#39;</span>Disabled<span class="pl-pds">&#39;</span></span>; </span><span class="pl-pse"><span class="pl-s2">?</span>&gt;</span>&lt;/<span class="pl-ent">td</span>&gt;</td>
      </tr>
      <tr>
        <td id="L511" class="blob-num js-line-number" data-line-number="511"></td>
        <td id="LC511" class="blob-code js-file-line">					&lt;/<span class="pl-ent">tr</span>&gt;</td>
      </tr>
      <tr>
        <td id="L512" class="blob-num js-line-number" data-line-number="512"></td>
        <td id="LC512" class="blob-code js-file-line">					&lt;<span class="pl-ent">tr</span> <span class="pl-e">class</span>=<span class="pl-s1"><span class="pl-pds">&quot;</span><span class="pl-pse">&lt;?php</span><span class="pl-s2"> <span class="pl-s3">echo</span> (<span class="pl-vo">$zlib_ok</span>) ? <span class="pl-s1"><span class="pl-pds">&#39;</span>enabled<span class="pl-pds">&#39;</span></span> : <span class="pl-s1"><span class="pl-pds">&#39;</span>disabled<span class="pl-pds">&#39;</span></span>; </span><span class="pl-pse"><span class="pl-s2">?</span>&gt;</span><span class="pl-pds">&quot;</span></span>&gt;</td>
      </tr>
      <tr>
        <td id="L513" class="blob-num js-line-number" data-line-number="513"></td>
        <td id="LC513" class="blob-code js-file-line">						&lt;<span class="pl-ent">td</span>&gt;&lt;<span class="pl-ent">a</span> <span class="pl-e">href</span>=<span class="pl-s1"><span class="pl-pds">&quot;</span>http://php.net/zlib<span class="pl-pds">&quot;</span></span>&gt;Zlib&lt;/<span class="pl-ent">a</span>&gt;&lt;/<span class="pl-ent">td</span>&gt;</td>
      </tr>
      <tr>
        <td id="L514" class="blob-num js-line-number" data-line-number="514"></td>
        <td id="LC514" class="blob-code js-file-line">						&lt;<span class="pl-ent">td</span>&gt;Enabled&lt;/<span class="pl-ent">td</span>&gt;</td>
      </tr>
      <tr>
        <td id="L515" class="blob-num js-line-number" data-line-number="515"></td>
        <td id="LC515" class="blob-code js-file-line">						&lt;<span class="pl-ent">td</span>&gt;<span class="pl-pse">&lt;?php</span><span class="pl-s2"> <span class="pl-s3">echo</span> (<span class="pl-vo">$zlib_ok</span>) ? <span class="pl-s1"><span class="pl-pds">&#39;</span>Enabled<span class="pl-pds">&#39;</span></span> : <span class="pl-s1"><span class="pl-pds">&#39;</span>Disabled<span class="pl-pds">&#39;</span></span>; </span><span class="pl-pse"><span class="pl-s2">?</span>&gt;</span>&lt;/<span class="pl-ent">td</span>&gt;</td>
      </tr>
      <tr>
        <td id="L516" class="blob-num js-line-number" data-line-number="516"></td>
        <td id="LC516" class="blob-code js-file-line">					&lt;/<span class="pl-ent">tr</span>&gt;</td>
      </tr>
      <tr>
        <td id="L517" class="blob-num js-line-number" data-line-number="517"></td>
        <td id="LC517" class="blob-code js-file-line">					&lt;<span class="pl-ent">tr</span> <span class="pl-e">class</span>=<span class="pl-s1"><span class="pl-pds">&quot;</span><span class="pl-pse">&lt;?php</span><span class="pl-s2"> <span class="pl-s3">echo</span> (<span class="pl-vo">$apc_ok</span>) ? <span class="pl-s1"><span class="pl-pds">&#39;</span>enabled<span class="pl-pds">&#39;</span></span> : <span class="pl-s1"><span class="pl-pds">&#39;</span>disabled<span class="pl-pds">&#39;</span></span>; </span><span class="pl-pse"><span class="pl-s2">?</span>&gt;</span><span class="pl-pds">&quot;</span></span>&gt;</td>
      </tr>
      <tr>
        <td id="L518" class="blob-num js-line-number" data-line-number="518"></td>
        <td id="LC518" class="blob-code js-file-line">						&lt;<span class="pl-ent">td</span>&gt;&lt;<span class="pl-ent">a</span> <span class="pl-e">href</span>=<span class="pl-s1"><span class="pl-pds">&quot;</span>http://php.net/apc<span class="pl-pds">&quot;</span></span>&gt;APC&lt;/<span class="pl-ent">a</span>&gt;&lt;/<span class="pl-ent">td</span>&gt;</td>
      </tr>
      <tr>
        <td id="L519" class="blob-num js-line-number" data-line-number="519"></td>
        <td id="LC519" class="blob-code js-file-line">						&lt;<span class="pl-ent">td</span>&gt;Enabled&lt;/<span class="pl-ent">td</span>&gt;</td>
      </tr>
      <tr>
        <td id="L520" class="blob-num js-line-number" data-line-number="520"></td>
        <td id="LC520" class="blob-code js-file-line">						&lt;<span class="pl-ent">td</span>&gt;<span class="pl-pse">&lt;?php</span><span class="pl-s2"> <span class="pl-s3">echo</span> (<span class="pl-vo">$apc_ok</span>) ? <span class="pl-s1"><span class="pl-pds">&#39;</span>Enabled<span class="pl-pds">&#39;</span></span> : <span class="pl-s1"><span class="pl-pds">&#39;</span>Disabled<span class="pl-pds">&#39;</span></span>; </span><span class="pl-pse"><span class="pl-s2">?</span>&gt;</span>&lt;/<span class="pl-ent">td</span>&gt;</td>
      </tr>
      <tr>
        <td id="L521" class="blob-num js-line-number" data-line-number="521"></td>
        <td id="LC521" class="blob-code js-file-line">					&lt;/<span class="pl-ent">tr</span>&gt;</td>
      </tr>
      <tr>
        <td id="L522" class="blob-num js-line-number" data-line-number="522"></td>
        <td id="LC522" class="blob-code js-file-line">					&lt;<span class="pl-ent">tr</span> <span class="pl-e">class</span>=<span class="pl-s1"><span class="pl-pds">&quot;</span><span class="pl-pse">&lt;?php</span><span class="pl-s2"> <span class="pl-s3">echo</span> (<span class="pl-vo">$xcache_ok</span>) ? <span class="pl-s1"><span class="pl-pds">&#39;</span>enabled<span class="pl-pds">&#39;</span></span> : <span class="pl-s1"><span class="pl-pds">&#39;</span>disabled<span class="pl-pds">&#39;</span></span>; </span><span class="pl-pse"><span class="pl-s2">?</span>&gt;</span><span class="pl-pds">&quot;</span></span>&gt;</td>
      </tr>
      <tr>
        <td id="L523" class="blob-num js-line-number" data-line-number="523"></td>
        <td id="LC523" class="blob-code js-file-line">						&lt;<span class="pl-ent">td</span>&gt;&lt;<span class="pl-ent">a</span> <span class="pl-e">href</span>=<span class="pl-s1"><span class="pl-pds">&quot;</span>http://xcache.lighttpd.net<span class="pl-pds">&quot;</span></span>&gt;XCache&lt;/<span class="pl-ent">a</span>&gt;&lt;/<span class="pl-ent">td</span>&gt;</td>
      </tr>
      <tr>
        <td id="L524" class="blob-num js-line-number" data-line-number="524"></td>
        <td id="LC524" class="blob-code js-file-line">						&lt;<span class="pl-ent">td</span>&gt;Enabled&lt;/<span class="pl-ent">td</span>&gt;</td>
      </tr>
      <tr>
        <td id="L525" class="blob-num js-line-number" data-line-number="525"></td>
        <td id="LC525" class="blob-code js-file-line">						&lt;<span class="pl-ent">td</span>&gt;<span class="pl-pse">&lt;?php</span><span class="pl-s2"> <span class="pl-s3">echo</span> (<span class="pl-vo">$xcache_ok</span>) ? <span class="pl-s1"><span class="pl-pds">&#39;</span>Enabled<span class="pl-pds">&#39;</span></span> : <span class="pl-s1"><span class="pl-pds">&#39;</span>Disabled<span class="pl-pds">&#39;</span></span>; </span><span class="pl-pse"><span class="pl-s2">?</span>&gt;</span>&lt;/<span class="pl-ent">td</span>&gt;</td>
      </tr>
      <tr>
        <td id="L526" class="blob-num js-line-number" data-line-number="526"></td>
        <td id="LC526" class="blob-code js-file-line">					&lt;/<span class="pl-ent">tr</span>&gt;</td>
      </tr>
      <tr>
        <td id="L527" class="blob-num js-line-number" data-line-number="527"></td>
        <td id="LC527" class="blob-code js-file-line">					&lt;<span class="pl-ent">tr</span> <span class="pl-e">class</span>=<span class="pl-s1"><span class="pl-pds">&quot;</span><span class="pl-pse">&lt;?php</span><span class="pl-s2"> <span class="pl-s3">echo</span> (<span class="pl-vo">$memcache_ok</span>) ? <span class="pl-s1"><span class="pl-pds">&#39;</span>enabled<span class="pl-pds">&#39;</span></span> : <span class="pl-s1"><span class="pl-pds">&#39;</span>disabled<span class="pl-pds">&#39;</span></span>; </span><span class="pl-pse"><span class="pl-s2">?</span>&gt;</span><span class="pl-pds">&quot;</span></span>&gt;</td>
      </tr>
      <tr>
        <td id="L528" class="blob-num js-line-number" data-line-number="528"></td>
        <td id="LC528" class="blob-code js-file-line">						&lt;<span class="pl-ent">td</span>&gt;&lt;<span class="pl-ent">a</span> <span class="pl-e">href</span>=<span class="pl-s1"><span class="pl-pds">&quot;</span>http://php.net/memcache<span class="pl-pds">&quot;</span></span>&gt;Memcache&lt;/<span class="pl-ent">a</span>&gt;&lt;/<span class="pl-ent">td</span>&gt;</td>
      </tr>
      <tr>
        <td id="L529" class="blob-num js-line-number" data-line-number="529"></td>
        <td id="LC529" class="blob-code js-file-line">						&lt;<span class="pl-ent">td</span>&gt;Enabled&lt;/<span class="pl-ent">td</span>&gt;</td>
      </tr>
      <tr>
        <td id="L530" class="blob-num js-line-number" data-line-number="530"></td>
        <td id="LC530" class="blob-code js-file-line">						&lt;<span class="pl-ent">td</span>&gt;<span class="pl-pse">&lt;?php</span><span class="pl-s2"> <span class="pl-s3">echo</span> (<span class="pl-vo">$memcache_ok</span>) ? <span class="pl-s1"><span class="pl-pds">&#39;</span>Enabled<span class="pl-pds">&#39;</span></span> : <span class="pl-s1"><span class="pl-pds">&#39;</span>Disabled<span class="pl-pds">&#39;</span></span>; </span><span class="pl-pse"><span class="pl-s2">?</span>&gt;</span>&lt;/<span class="pl-ent">td</span>&gt;</td>
      </tr>
      <tr>
        <td id="L531" class="blob-num js-line-number" data-line-number="531"></td>
        <td id="LC531" class="blob-code js-file-line">					&lt;/<span class="pl-ent">tr</span>&gt;</td>
      </tr>
      <tr>
        <td id="L532" class="blob-num js-line-number" data-line-number="532"></td>
        <td id="LC532" class="blob-code js-file-line">					&lt;<span class="pl-ent">tr</span> <span class="pl-e">class</span>=<span class="pl-s1"><span class="pl-pds">&quot;</span><span class="pl-pse">&lt;?php</span><span class="pl-s2"> <span class="pl-s3">echo</span> (<span class="pl-vo">$memcached_ok</span>) ? <span class="pl-s1"><span class="pl-pds">&#39;</span>enabled<span class="pl-pds">&#39;</span></span> : <span class="pl-s1"><span class="pl-pds">&#39;</span>disabled<span class="pl-pds">&#39;</span></span>; </span><span class="pl-pse"><span class="pl-s2">?</span>&gt;</span><span class="pl-pds">&quot;</span></span>&gt;</td>
      </tr>
      <tr>
        <td id="L533" class="blob-num js-line-number" data-line-number="533"></td>
        <td id="LC533" class="blob-code js-file-line">						&lt;<span class="pl-ent">td</span>&gt;&lt;<span class="pl-ent">a</span> <span class="pl-e">href</span>=<span class="pl-s1"><span class="pl-pds">&quot;</span>http://php.net/memcached<span class="pl-pds">&quot;</span></span>&gt;Memcached&lt;/<span class="pl-ent">a</span>&gt;&lt;/<span class="pl-ent">td</span>&gt;</td>
      </tr>
      <tr>
        <td id="L534" class="blob-num js-line-number" data-line-number="534"></td>
        <td id="LC534" class="blob-code js-file-line">						&lt;<span class="pl-ent">td</span>&gt;Enabled&lt;/<span class="pl-ent">td</span>&gt;</td>
      </tr>
      <tr>
        <td id="L535" class="blob-num js-line-number" data-line-number="535"></td>
        <td id="LC535" class="blob-code js-file-line">						&lt;<span class="pl-ent">td</span>&gt;<span class="pl-pse">&lt;?php</span><span class="pl-s2"> <span class="pl-s3">echo</span> (<span class="pl-vo">$memcached_ok</span>) ? <span class="pl-s1"><span class="pl-pds">&#39;</span>Enabled<span class="pl-pds">&#39;</span></span> : <span class="pl-s1"><span class="pl-pds">&#39;</span>Disabled<span class="pl-pds">&#39;</span></span>; </span><span class="pl-pse"><span class="pl-s2">?</span>&gt;</span>&lt;/<span class="pl-ent">td</span>&gt;</td>
      </tr>
      <tr>
        <td id="L536" class="blob-num js-line-number" data-line-number="536"></td>
        <td id="LC536" class="blob-code js-file-line">					&lt;/<span class="pl-ent">tr</span>&gt;</td>
      </tr>
      <tr>
        <td id="L537" class="blob-num js-line-number" data-line-number="537"></td>
        <td id="LC537" class="blob-code js-file-line">					&lt;<span class="pl-ent">tr</span> <span class="pl-e">class</span>=<span class="pl-s1"><span class="pl-pds">&quot;</span><span class="pl-pse">&lt;?php</span><span class="pl-s2"> <span class="pl-s3">echo</span> (<span class="pl-vo">$pdo_ok</span>) ? <span class="pl-s1"><span class="pl-pds">&#39;</span>enabled<span class="pl-pds">&#39;</span></span> : <span class="pl-s1"><span class="pl-pds">&#39;</span>disabled<span class="pl-pds">&#39;</span></span>; </span><span class="pl-pse"><span class="pl-s2">?</span>&gt;</span><span class="pl-pds">&quot;</span></span>&gt;</td>
      </tr>
      <tr>
        <td id="L538" class="blob-num js-line-number" data-line-number="538"></td>
        <td id="LC538" class="blob-code js-file-line">						&lt;<span class="pl-ent">td</span>&gt;&lt;<span class="pl-ent">a</span> <span class="pl-e">href</span>=<span class="pl-s1"><span class="pl-pds">&quot;</span>http://php.net/pdo<span class="pl-pds">&quot;</span></span>&gt;PDO&lt;/<span class="pl-ent">a</span>&gt;&lt;/<span class="pl-ent">td</span>&gt;</td>
      </tr>
      <tr>
        <td id="L539" class="blob-num js-line-number" data-line-number="539"></td>
        <td id="LC539" class="blob-code js-file-line">						&lt;<span class="pl-ent">td</span>&gt;Enabled&lt;/<span class="pl-ent">td</span>&gt;</td>
      </tr>
      <tr>
        <td id="L540" class="blob-num js-line-number" data-line-number="540"></td>
        <td id="LC540" class="blob-code js-file-line">						&lt;<span class="pl-ent">td</span>&gt;<span class="pl-pse">&lt;?php</span><span class="pl-s2"> <span class="pl-s3">echo</span> (<span class="pl-vo">$pdo_ok</span>) ? <span class="pl-s1"><span class="pl-pds">&#39;</span>Enabled<span class="pl-pds">&#39;</span></span> : <span class="pl-s1"><span class="pl-pds">&#39;</span>Disabled<span class="pl-pds">&#39;</span></span>; </span><span class="pl-pse"><span class="pl-s2">?</span>&gt;</span>&lt;/<span class="pl-ent">td</span>&gt;</td>
      </tr>
      <tr>
        <td id="L541" class="blob-num js-line-number" data-line-number="541"></td>
        <td id="LC541" class="blob-code js-file-line">					&lt;/<span class="pl-ent">tr</span>&gt;</td>
      </tr>
      <tr>
        <td id="L542" class="blob-num js-line-number" data-line-number="542"></td>
        <td id="LC542" class="blob-code js-file-line">					&lt;<span class="pl-ent">tr</span> <span class="pl-e">class</span>=<span class="pl-s1"><span class="pl-pds">&quot;</span><span class="pl-pse">&lt;?php</span><span class="pl-s2"> <span class="pl-s3">echo</span> (<span class="pl-vo">$pdo_sqlite_ok</span>) ? <span class="pl-s1"><span class="pl-pds">&#39;</span>enabled<span class="pl-pds">&#39;</span></span> : <span class="pl-s1"><span class="pl-pds">&#39;</span>disabled<span class="pl-pds">&#39;</span></span>; </span><span class="pl-pse"><span class="pl-s2">?</span>&gt;</span><span class="pl-pds">&quot;</span></span>&gt;</td>
      </tr>
      <tr>
        <td id="L543" class="blob-num js-line-number" data-line-number="543"></td>
        <td id="LC543" class="blob-code js-file-line">						&lt;<span class="pl-ent">td</span>&gt;&lt;<span class="pl-ent">a</span> <span class="pl-e">href</span>=<span class="pl-s1"><span class="pl-pds">&quot;</span>http://php.net/pdo-sqlite<span class="pl-pds">&quot;</span></span>&gt;PDO-SQLite&lt;/<span class="pl-ent">a</span>&gt;&lt;/<span class="pl-ent">td</span>&gt;</td>
      </tr>
      <tr>
        <td id="L544" class="blob-num js-line-number" data-line-number="544"></td>
        <td id="LC544" class="blob-code js-file-line">						&lt;<span class="pl-ent">td</span>&gt;Enabled&lt;/<span class="pl-ent">td</span>&gt;</td>
      </tr>
      <tr>
        <td id="L545" class="blob-num js-line-number" data-line-number="545"></td>
        <td id="LC545" class="blob-code js-file-line">						&lt;<span class="pl-ent">td</span>&gt;<span class="pl-pse">&lt;?php</span><span class="pl-s2"> <span class="pl-s3">echo</span> (<span class="pl-vo">$pdo_sqlite_ok</span>) ? <span class="pl-s1"><span class="pl-pds">&#39;</span>Enabled<span class="pl-pds">&#39;</span></span> : <span class="pl-s1"><span class="pl-pds">&#39;</span>Disabled<span class="pl-pds">&#39;</span></span>; </span><span class="pl-pse"><span class="pl-s2">?</span>&gt;</span>&lt;/<span class="pl-ent">td</span>&gt;</td>
      </tr>
      <tr>
        <td id="L546" class="blob-num js-line-number" data-line-number="546"></td>
        <td id="LC546" class="blob-code js-file-line">					&lt;/<span class="pl-ent">tr</span>&gt;</td>
      </tr>
      <tr>
        <td id="L547" class="blob-num js-line-number" data-line-number="547"></td>
        <td id="LC547" class="blob-code js-file-line">					&lt;<span class="pl-ent">tr</span> <span class="pl-e">class</span>=<span class="pl-s1"><span class="pl-pds">&quot;</span><span class="pl-pse">&lt;?php</span><span class="pl-s2"> <span class="pl-s3">echo</span> (<span class="pl-vo">$sqlite2_ok</span>) ? <span class="pl-s1"><span class="pl-pds">&#39;</span>enabled<span class="pl-pds">&#39;</span></span> : <span class="pl-s1"><span class="pl-pds">&#39;</span>disabled<span class="pl-pds">&#39;</span></span>; </span><span class="pl-pse"><span class="pl-s2">?</span>&gt;</span><span class="pl-pds">&quot;</span></span>&gt;</td>
      </tr>
      <tr>
        <td id="L548" class="blob-num js-line-number" data-line-number="548"></td>
        <td id="LC548" class="blob-code js-file-line">						&lt;<span class="pl-ent">td</span>&gt;&lt;<span class="pl-ent">a</span> <span class="pl-e">href</span>=<span class="pl-s1"><span class="pl-pds">&quot;</span>http://php.net/sqlite<span class="pl-pds">&quot;</span></span>&gt;SQLite 2&lt;/<span class="pl-ent">a</span>&gt;&lt;/<span class="pl-ent">td</span>&gt;</td>
      </tr>
      <tr>
        <td id="L549" class="blob-num js-line-number" data-line-number="549"></td>
        <td id="LC549" class="blob-code js-file-line">						&lt;<span class="pl-ent">td</span>&gt;Enabled&lt;/<span class="pl-ent">td</span>&gt;</td>
      </tr>
      <tr>
        <td id="L550" class="blob-num js-line-number" data-line-number="550"></td>
        <td id="LC550" class="blob-code js-file-line">						&lt;<span class="pl-ent">td</span>&gt;<span class="pl-pse">&lt;?php</span><span class="pl-s2"> <span class="pl-s3">echo</span> (<span class="pl-vo">$sqlite2_ok</span>) ? <span class="pl-s1"><span class="pl-pds">&#39;</span>Enabled<span class="pl-pds">&#39;</span></span> : <span class="pl-s1"><span class="pl-pds">&#39;</span>Disabled<span class="pl-pds">&#39;</span></span>; </span><span class="pl-pse"><span class="pl-s2">?</span>&gt;</span>&lt;/<span class="pl-ent">td</span>&gt;</td>
      </tr>
      <tr>
        <td id="L551" class="blob-num js-line-number" data-line-number="551"></td>
        <td id="LC551" class="blob-code js-file-line">					&lt;/<span class="pl-ent">tr</span>&gt;</td>
      </tr>
      <tr>
        <td id="L552" class="blob-num js-line-number" data-line-number="552"></td>
        <td id="LC552" class="blob-code js-file-line">					&lt;<span class="pl-ent">tr</span> <span class="pl-e">class</span>=<span class="pl-s1"><span class="pl-pds">&quot;</span><span class="pl-pse">&lt;?php</span><span class="pl-s2"> <span class="pl-s3">echo</span> (<span class="pl-vo">$sqlite3_ok</span>) ? <span class="pl-s1"><span class="pl-pds">&#39;</span>enabled<span class="pl-pds">&#39;</span></span> : <span class="pl-s1"><span class="pl-pds">&#39;</span>disabled<span class="pl-pds">&#39;</span></span>; </span><span class="pl-pse"><span class="pl-s2">?</span>&gt;</span><span class="pl-pds">&quot;</span></span>&gt;</td>
      </tr>
      <tr>
        <td id="L553" class="blob-num js-line-number" data-line-number="553"></td>
        <td id="LC553" class="blob-code js-file-line">						&lt;<span class="pl-ent">td</span>&gt;&lt;<span class="pl-ent">a</span> <span class="pl-e">href</span>=<span class="pl-s1"><span class="pl-pds">&quot;</span>http://php.net/sqlite3<span class="pl-pds">&quot;</span></span>&gt;SQLite 3&lt;/<span class="pl-ent">a</span>&gt;&lt;/<span class="pl-ent">td</span>&gt;</td>
      </tr>
      <tr>
        <td id="L554" class="blob-num js-line-number" data-line-number="554"></td>
        <td id="LC554" class="blob-code js-file-line">						&lt;<span class="pl-ent">td</span>&gt;Enabled&lt;/<span class="pl-ent">td</span>&gt;</td>
      </tr>
      <tr>
        <td id="L555" class="blob-num js-line-number" data-line-number="555"></td>
        <td id="LC555" class="blob-code js-file-line">						&lt;<span class="pl-ent">td</span>&gt;<span class="pl-pse">&lt;?php</span><span class="pl-s2"> <span class="pl-s3">echo</span> (<span class="pl-vo">$sqlite3_ok</span>) ? <span class="pl-s1"><span class="pl-pds">&#39;</span>Enabled<span class="pl-pds">&#39;</span></span> : <span class="pl-s1"><span class="pl-pds">&#39;</span>Disabled<span class="pl-pds">&#39;</span></span>; </span><span class="pl-pse"><span class="pl-s2">?</span>&gt;</span>&lt;/<span class="pl-ent">td</span>&gt;</td>
      </tr>
      <tr>
        <td id="L556" class="blob-num js-line-number" data-line-number="556"></td>
        <td id="LC556" class="blob-code js-file-line">					&lt;/<span class="pl-ent">tr</span>&gt;</td>
      </tr>
      <tr>
        <td id="L557" class="blob-num js-line-number" data-line-number="557"></td>
        <td id="LC557" class="blob-code js-file-line">				&lt;/<span class="pl-ent">tbody</span>&gt;</td>
      </tr>
      <tr>
        <td id="L558" class="blob-num js-line-number" data-line-number="558"></td>
        <td id="LC558" class="blob-code js-file-line">			&lt;/<span class="pl-ent">table</span>&gt;</td>
      </tr>
      <tr>
        <td id="L559" class="blob-num js-line-number" data-line-number="559"></td>
        <td id="LC559" class="blob-code js-file-line">
</td>
      </tr>
      <tr>
        <td id="L560" class="blob-num js-line-number" data-line-number="560"></td>
        <td id="LC560" class="blob-code js-file-line">			&lt;<span class="pl-ent">h3</span>&gt;Settings for php.ini&lt;/<span class="pl-ent">h3</span>&gt;</td>
      </tr>
      <tr>
        <td id="L561" class="blob-num js-line-number" data-line-number="561"></td>
        <td id="LC561" class="blob-code js-file-line">			&lt;<span class="pl-ent">table</span> <span class="pl-e">cellpadding</span>=<span class="pl-s1"><span class="pl-pds">&quot;</span>0<span class="pl-pds">&quot;</span></span> <span class="pl-e">cellspacing</span>=<span class="pl-s1"><span class="pl-pds">&quot;</span>0<span class="pl-pds">&quot;</span></span> <span class="pl-e">border</span>=<span class="pl-s1"><span class="pl-pds">&quot;</span>0<span class="pl-pds">&quot;</span></span> <span class="pl-e">width</span>=<span class="pl-s1"><span class="pl-pds">&quot;</span>100%<span class="pl-pds">&quot;</span></span> <span class="pl-e">class</span>=<span class="pl-s1"><span class="pl-pds">&quot;</span>chart<span class="pl-pds">&quot;</span></span>&gt;</td>
      </tr>
      <tr>
        <td id="L562" class="blob-num js-line-number" data-line-number="562"></td>
        <td id="LC562" class="blob-code js-file-line">				&lt;<span class="pl-ent">thead</span>&gt;</td>
      </tr>
      <tr>
        <td id="L563" class="blob-num js-line-number" data-line-number="563"></td>
        <td id="LC563" class="blob-code js-file-line">					&lt;<span class="pl-ent">tr</span>&gt;</td>
      </tr>
      <tr>
        <td id="L564" class="blob-num js-line-number" data-line-number="564"></td>
        <td id="LC564" class="blob-code js-file-line">						&lt;<span class="pl-ent">th</span>&gt;Test&lt;/<span class="pl-ent">th</span>&gt;</td>
      </tr>
      <tr>
        <td id="L565" class="blob-num js-line-number" data-line-number="565"></td>
        <td id="LC565" class="blob-code js-file-line">						&lt;<span class="pl-ent">th</span>&gt;Would Like To Be&lt;/<span class="pl-ent">th</span>&gt;</td>
      </tr>
      <tr>
        <td id="L566" class="blob-num js-line-number" data-line-number="566"></td>
        <td id="LC566" class="blob-code js-file-line">						&lt;<span class="pl-ent">th</span>&gt;What You Have&lt;/<span class="pl-ent">th</span>&gt;</td>
      </tr>
      <tr>
        <td id="L567" class="blob-num js-line-number" data-line-number="567"></td>
        <td id="LC567" class="blob-code js-file-line">					&lt;/<span class="pl-ent">tr</span>&gt;</td>
      </tr>
      <tr>
        <td id="L568" class="blob-num js-line-number" data-line-number="568"></td>
        <td id="LC568" class="blob-code js-file-line">				&lt;/<span class="pl-ent">thead</span>&gt;</td>
      </tr>
      <tr>
        <td id="L569" class="blob-num js-line-number" data-line-number="569"></td>
        <td id="LC569" class="blob-code js-file-line">				&lt;<span class="pl-ent">tbody</span>&gt;</td>
      </tr>
      <tr>
        <td id="L570" class="blob-num js-line-number" data-line-number="570"></td>
        <td id="LC570" class="blob-code js-file-line">					&lt;<span class="pl-ent">tr</span> <span class="pl-e">class</span>=<span class="pl-s1"><span class="pl-pds">&quot;</span><span class="pl-pse">&lt;?php</span><span class="pl-s2"> <span class="pl-s3">echo</span> (<span class="pl-k">!</span><span class="pl-vo">$ini_open_basedir</span>) ? <span class="pl-s1"><span class="pl-pds">&#39;</span>enabled<span class="pl-pds">&#39;</span></span> : <span class="pl-s1"><span class="pl-pds">&#39;</span>disabled<span class="pl-pds">&#39;</span></span>; </span><span class="pl-pse"><span class="pl-s2">?</span>&gt;</span><span class="pl-pds">&quot;</span></span>&gt;</td>
      </tr>
      <tr>
        <td id="L571" class="blob-num js-line-number" data-line-number="571"></td>
        <td id="LC571" class="blob-code js-file-line">						&lt;<span class="pl-ent">td</span>&gt;&lt;<span class="pl-ent">a</span> <span class="pl-e">href</span>=<span class="pl-s1"><span class="pl-pds">&quot;</span>http://php.net/open_basedir<span class="pl-pds">&quot;</span></span>&gt;open_basedir&lt;/<span class="pl-ent">a</span>&gt;&lt;/<span class="pl-ent">td</span>&gt;</td>
      </tr>
      <tr>
        <td id="L572" class="blob-num js-line-number" data-line-number="572"></td>
        <td id="LC572" class="blob-code js-file-line">						&lt;<span class="pl-ent">td</span>&gt;off&lt;/<span class="pl-ent">td</span>&gt;</td>
      </tr>
      <tr>
        <td id="L573" class="blob-num js-line-number" data-line-number="573"></td>
        <td id="LC573" class="blob-code js-file-line">						&lt;<span class="pl-ent">td</span>&gt;<span class="pl-pse">&lt;?php</span><span class="pl-s2"> <span class="pl-s3">echo</span> (<span class="pl-vo">$ini_open_basedir</span>) ? <span class="pl-s1"><span class="pl-pds">&#39;</span>on<span class="pl-pds">&#39;</span></span> : <span class="pl-s1"><span class="pl-pds">&#39;</span>off<span class="pl-pds">&#39;</span></span>; </span><span class="pl-pse"><span class="pl-s2">?</span>&gt;</span>&lt;/<span class="pl-ent">td</span>&gt;</td>
      </tr>
      <tr>
        <td id="L574" class="blob-num js-line-number" data-line-number="574"></td>
        <td id="LC574" class="blob-code js-file-line">					&lt;/<span class="pl-ent">tr</span>&gt;</td>
      </tr>
      <tr>
        <td id="L575" class="blob-num js-line-number" data-line-number="575"></td>
        <td id="LC575" class="blob-code js-file-line">					&lt;<span class="pl-ent">tr</span> <span class="pl-e">class</span>=<span class="pl-s1"><span class="pl-pds">&quot;</span><span class="pl-pse">&lt;?php</span><span class="pl-s2"> <span class="pl-s3">echo</span> (<span class="pl-k">!</span><span class="pl-vo">$ini_safe_mode</span>) ? <span class="pl-s1"><span class="pl-pds">&#39;</span>enabled<span class="pl-pds">&#39;</span></span> : <span class="pl-s1"><span class="pl-pds">&#39;</span>disabled<span class="pl-pds">&#39;</span></span>; </span><span class="pl-pse"><span class="pl-s2">?</span>&gt;</span><span class="pl-pds">&quot;</span></span>&gt;</td>
      </tr>
      <tr>
        <td id="L576" class="blob-num js-line-number" data-line-number="576"></td>
        <td id="LC576" class="blob-code js-file-line">						&lt;<span class="pl-ent">td</span>&gt;&lt;<span class="pl-ent">a</span> <span class="pl-e">href</span>=<span class="pl-s1"><span class="pl-pds">&quot;</span>http://php.net/safe_mode<span class="pl-pds">&quot;</span></span>&gt;safe_mode&lt;/<span class="pl-ent">a</span>&gt;&lt;/<span class="pl-ent">td</span>&gt;</td>
      </tr>
      <tr>
        <td id="L577" class="blob-num js-line-number" data-line-number="577"></td>
        <td id="LC577" class="blob-code js-file-line">						&lt;<span class="pl-ent">td</span>&gt;off&lt;/<span class="pl-ent">td</span>&gt;</td>
      </tr>
      <tr>
        <td id="L578" class="blob-num js-line-number" data-line-number="578"></td>
        <td id="LC578" class="blob-code js-file-line">						&lt;<span class="pl-ent">td</span>&gt;<span class="pl-pse">&lt;?php</span><span class="pl-s2"> <span class="pl-s3">echo</span> (<span class="pl-vo">$ini_safe_mode</span>) ? <span class="pl-s1"><span class="pl-pds">&#39;</span>on<span class="pl-pds">&#39;</span></span> : <span class="pl-s1"><span class="pl-pds">&#39;</span>off<span class="pl-pds">&#39;</span></span>; </span><span class="pl-pse"><span class="pl-s2">?</span>&gt;</span>&lt;/<span class="pl-ent">td</span>&gt;</td>
      </tr>
      <tr>
        <td id="L579" class="blob-num js-line-number" data-line-number="579"></td>
        <td id="LC579" class="blob-code js-file-line">					&lt;/<span class="pl-ent">tr</span>&gt;</td>
      </tr>
      <tr>
        <td id="L580" class="blob-num js-line-number" data-line-number="580"></td>
        <td id="LC580" class="blob-code js-file-line">					&lt;<span class="pl-ent">tr</span> <span class="pl-e">class</span>=<span class="pl-s1"><span class="pl-pds">&quot;</span><span class="pl-pse">&lt;?php</span><span class="pl-s2"> <span class="pl-s3">echo</span> (<span class="pl-vo">$ini_zend_enable_gc</span>) ? <span class="pl-s1"><span class="pl-pds">&#39;</span>enabled<span class="pl-pds">&#39;</span></span> : <span class="pl-s1"><span class="pl-pds">&#39;</span>disabled<span class="pl-pds">&#39;</span></span>; </span><span class="pl-pse"><span class="pl-s2">?</span>&gt;</span><span class="pl-pds">&quot;</span></span>&gt;</td>
      </tr>
      <tr>
        <td id="L581" class="blob-num js-line-number" data-line-number="581"></td>
        <td id="LC581" class="blob-code js-file-line">						&lt;<span class="pl-ent">td</span>&gt;&lt;<span class="pl-ent">a</span> <span class="pl-e">href</span>=<span class="pl-s1"><span class="pl-pds">&quot;</span>http://php.net/zend.enable_gc<span class="pl-pds">&quot;</span></span>&gt;zend.enable_gc&lt;/<span class="pl-ent">a</span>&gt;&lt;/<span class="pl-ent">td</span>&gt;</td>
      </tr>
      <tr>
        <td id="L582" class="blob-num js-line-number" data-line-number="582"></td>
        <td id="LC582" class="blob-code js-file-line">						&lt;<span class="pl-ent">td</span>&gt;on&lt;/<span class="pl-ent">td</span>&gt;</td>
      </tr>
      <tr>
        <td id="L583" class="blob-num js-line-number" data-line-number="583"></td>
        <td id="LC583" class="blob-code js-file-line">						&lt;<span class="pl-ent">td</span>&gt;<span class="pl-pse">&lt;?php</span><span class="pl-s2"> <span class="pl-s3">echo</span> (<span class="pl-vo">$ini_zend_enable_gc</span>) ? <span class="pl-s1"><span class="pl-pds">&#39;</span>on<span class="pl-pds">&#39;</span></span> : <span class="pl-s1"><span class="pl-pds">&#39;</span>off<span class="pl-pds">&#39;</span></span>; </span><span class="pl-pse"><span class="pl-s2">?</span>&gt;</span>&lt;/<span class="pl-ent">td</span>&gt;</td>
      </tr>
      <tr>
        <td id="L584" class="blob-num js-line-number" data-line-number="584"></td>
        <td id="LC584" class="blob-code js-file-line">					&lt;/<span class="pl-ent">tr</span>&gt;</td>
      </tr>
      <tr>
        <td id="L585" class="blob-num js-line-number" data-line-number="585"></td>
        <td id="LC585" class="blob-code js-file-line">				&lt;/<span class="pl-ent">tbody</span>&gt;</td>
      </tr>
      <tr>
        <td id="L586" class="blob-num js-line-number" data-line-number="586"></td>
        <td id="LC586" class="blob-code js-file-line">			&lt;/<span class="pl-ent">table</span>&gt;</td>
      </tr>
      <tr>
        <td id="L587" class="blob-num js-line-number" data-line-number="587"></td>
        <td id="LC587" class="blob-code js-file-line">
</td>
      </tr>
      <tr>
        <td id="L588" class="blob-num js-line-number" data-line-number="588"></td>
        <td id="LC588" class="blob-code js-file-line">			&lt;<span class="pl-ent">h3</span>&gt;Other&lt;/<span class="pl-ent">h3</span>&gt;</td>
      </tr>
      <tr>
        <td id="L589" class="blob-num js-line-number" data-line-number="589"></td>
        <td id="LC589" class="blob-code js-file-line">			&lt;<span class="pl-ent">table</span> <span class="pl-e">cellpadding</span>=<span class="pl-s1"><span class="pl-pds">&quot;</span>0<span class="pl-pds">&quot;</span></span> <span class="pl-e">cellspacing</span>=<span class="pl-s1"><span class="pl-pds">&quot;</span>0<span class="pl-pds">&quot;</span></span> <span class="pl-e">border</span>=<span class="pl-s1"><span class="pl-pds">&quot;</span>0<span class="pl-pds">&quot;</span></span> <span class="pl-e">width</span>=<span class="pl-s1"><span class="pl-pds">&quot;</span>100%<span class="pl-pds">&quot;</span></span> <span class="pl-e">class</span>=<span class="pl-s1"><span class="pl-pds">&quot;</span>chart<span class="pl-pds">&quot;</span></span>&gt;</td>
      </tr>
      <tr>
        <td id="L590" class="blob-num js-line-number" data-line-number="590"></td>
        <td id="LC590" class="blob-code js-file-line">				&lt;<span class="pl-ent">thead</span>&gt;</td>
      </tr>
      <tr>
        <td id="L591" class="blob-num js-line-number" data-line-number="591"></td>
        <td id="LC591" class="blob-code js-file-line">					&lt;<span class="pl-ent">tr</span>&gt;</td>
      </tr>
      <tr>
        <td id="L592" class="blob-num js-line-number" data-line-number="592"></td>
        <td id="LC592" class="blob-code js-file-line">						&lt;<span class="pl-ent">th</span>&gt;Test&lt;/<span class="pl-ent">th</span>&gt;</td>
      </tr>
      <tr>
        <td id="L593" class="blob-num js-line-number" data-line-number="593"></td>
        <td id="LC593" class="blob-code js-file-line">						&lt;<span class="pl-ent">th</span>&gt;Would Like To Be&lt;/<span class="pl-ent">th</span>&gt;</td>
      </tr>
      <tr>
        <td id="L594" class="blob-num js-line-number" data-line-number="594"></td>
        <td id="LC594" class="blob-code js-file-line">						&lt;<span class="pl-ent">th</span>&gt;What You Have&lt;/<span class="pl-ent">th</span>&gt;</td>
      </tr>
      <tr>
        <td id="L595" class="blob-num js-line-number" data-line-number="595"></td>
        <td id="LC595" class="blob-code js-file-line">					&lt;/<span class="pl-ent">tr</span>&gt;</td>
      </tr>
      <tr>
        <td id="L596" class="blob-num js-line-number" data-line-number="596"></td>
        <td id="LC596" class="blob-code js-file-line">				&lt;/<span class="pl-ent">thead</span>&gt;</td>
      </tr>
      <tr>
        <td id="L597" class="blob-num js-line-number" data-line-number="597"></td>
        <td id="LC597" class="blob-code js-file-line">				&lt;<span class="pl-ent">tbody</span>&gt;</td>
      </tr>
      <tr>
        <td id="L598" class="blob-num js-line-number" data-line-number="598"></td>
        <td id="LC598" class="blob-code js-file-line">					&lt;<span class="pl-ent">tr</span> <span class="pl-e">class</span>=<span class="pl-s1"><span class="pl-pds">&quot;</span><span class="pl-pse">&lt;?php</span><span class="pl-s2"> <span class="pl-s3">echo</span> (<span class="pl-vo">$int64_ok</span>) ? <span class="pl-s1"><span class="pl-pds">&#39;</span>enabled<span class="pl-pds">&#39;</span></span> : <span class="pl-s1"><span class="pl-pds">&#39;</span>disabled<span class="pl-pds">&#39;</span></span>; </span><span class="pl-pse"><span class="pl-s2">?</span>&gt;</span><span class="pl-pds">&quot;</span></span>&gt;</td>
      </tr>
      <tr>
        <td id="L599" class="blob-num js-line-number" data-line-number="599"></td>
        <td id="LC599" class="blob-code js-file-line">						&lt;<span class="pl-ent">td</span>&gt;&lt;<span class="pl-ent">a</span> <span class="pl-e">href</span>=<span class="pl-s1"><span class="pl-pds">&quot;</span>https://aws.amazon.com/amis/4158<span class="pl-pds">&quot;</span></span>&gt;Architecture&lt;/<span class="pl-ent">a</span>&gt;&lt;/<span class="pl-ent">td</span>&gt;</td>
      </tr>
      <tr>
        <td id="L600" class="blob-num js-line-number" data-line-number="600"></td>
        <td id="LC600" class="blob-code js-file-line">						&lt;<span class="pl-ent">td</span>&gt;64-bit&lt;/<span class="pl-ent">td</span>&gt;</td>
      </tr>
      <tr>
        <td id="L601" class="blob-num js-line-number" data-line-number="601"></td>
        <td id="LC601" class="blob-code js-file-line">						&lt;<span class="pl-ent">td</span>&gt;<span class="pl-pse">&lt;?php</span><span class="pl-s2"> <span class="pl-s3">echo</span> (<span class="pl-vo">$int64_ok</span>) ? <span class="pl-s1"><span class="pl-pds">&#39;</span>64-bit<span class="pl-pds">&#39;</span></span> : <span class="pl-s1"><span class="pl-pds">&#39;</span>32-bit<span class="pl-pds">&#39;</span></span>; </span><span class="pl-pse"><span class="pl-s2">?</span>&gt;</span><span class="pl-pse">&lt;?php</span><span class="pl-s2"> <span class="pl-k">if</span> (is_windows()): </span><span class="pl-pse"><span class="pl-s2">?</span>&gt;</span></td>
      </tr>
      <tr>
        <td id="L602" class="blob-num js-line-number" data-line-number="602"></td>
        <td id="LC602" class="blob-code js-file-line">						(&lt;<span class="pl-ent">a</span> <span class="pl-e">href</span>=<span class="pl-s1"><span class="pl-pds">&quot;</span>#win64<span class="pl-pds">&quot;</span></span>&gt;why?&lt;/<span class="pl-ent">a</span>&gt;)</td>
      </tr>
      <tr>
        <td id="L603" class="blob-num js-line-number" data-line-number="603"></td>
        <td id="LC603" class="blob-code js-file-line">						<span class="pl-pse">&lt;?php</span><span class="pl-s2"> <span class="pl-k">endif</span>; </span><span class="pl-pse"><span class="pl-s2">?</span>&gt;</span>&lt;/<span class="pl-ent">td</span>&gt;</td>
      </tr>
      <tr>
        <td id="L604" class="blob-num js-line-number" data-line-number="604"></td>
        <td id="LC604" class="blob-code js-file-line">					&lt;/<span class="pl-ent">tr</span>&gt;</td>
      </tr>
      <tr>
        <td id="L605" class="blob-num js-line-number" data-line-number="605"></td>
        <td id="LC605" class="blob-code js-file-line">				&lt;/<span class="pl-ent">tbody</span>&gt;</td>
      </tr>
      <tr>
        <td id="L606" class="blob-num js-line-number" data-line-number="606"></td>
        <td id="LC606" class="blob-code js-file-line">			&lt;/<span class="pl-ent">table</span>&gt;</td>
      </tr>
      <tr>
        <td id="L607" class="blob-num js-line-number" data-line-number="607"></td>
        <td id="LC607" class="blob-code js-file-line">
</td>
      </tr>
      <tr>
        <td id="L608" class="blob-num js-line-number" data-line-number="608"></td>
        <td id="LC608" class="blob-code js-file-line">			&lt;<span class="pl-ent">br</span>&gt;</td>
      </tr>
      <tr>
        <td id="L609" class="blob-num js-line-number" data-line-number="609"></td>
        <td id="LC609" class="blob-code js-file-line">		&lt;/<span class="pl-ent">div</span>&gt;</td>
      </tr>
      <tr>
        <td id="L610" class="blob-num js-line-number" data-line-number="610"></td>
        <td id="LC610" class="blob-code js-file-line">
</td>
      </tr>
      <tr>
        <td id="L611" class="blob-num js-line-number" data-line-number="611"></td>
        <td id="LC611" class="blob-code js-file-line">		<span class="pl-pse">&lt;?php</span><span class="pl-s2"> <span class="pl-k">if</span> (<span class="pl-vo">$compatiblity</span> <span class="pl-k">==</span> <span class="pl-c1">REQUIREMENTS_ALL_MET</span>): </span><span class="pl-pse"><span class="pl-s2">?</span>&gt;</span></td>
      </tr>
      <tr>
        <td id="L612" class="blob-num js-line-number" data-line-number="612"></td>
        <td id="LC612" class="blob-code js-file-line">		&lt;<span class="pl-ent">div</span> <span class="pl-e">class</span>=<span class="pl-s1"><span class="pl-pds">&quot;</span>chunk important ok<span class="pl-pds">&quot;</span></span>&gt;</td>
      </tr>
      <tr>
        <td id="L613" class="blob-num js-line-number" data-line-number="613"></td>
        <td id="LC613" class="blob-code js-file-line">			&lt;<span class="pl-ent">h3</span>&gt;Bottom Line: Yes, you can!&lt;/<span class="pl-ent">h3</span>&gt;</td>
      </tr>
      <tr>
        <td id="L614" class="blob-num js-line-number" data-line-number="614"></td>
        <td id="LC614" class="blob-code js-file-line">			&lt;<span class="pl-ent">p</span>&gt;Your PHP environment is ready to go, and can take advantage of all possible features!&lt;/<span class="pl-ent">p</span>&gt;</td>
      </tr>
      <tr>
        <td id="L615" class="blob-num js-line-number" data-line-number="615"></td>
        <td id="LC615" class="blob-code js-file-line">		&lt;/<span class="pl-ent">div</span>&gt;</td>
      </tr>
      <tr>
        <td id="L616" class="blob-num js-line-number" data-line-number="616"></td>
        <td id="LC616" class="blob-code js-file-line">		&lt;<span class="pl-ent">div</span> <span class="pl-e">class</span>=<span class="pl-s1"><span class="pl-pds">&quot;</span>chunk<span class="pl-pds">&quot;</span></span>&gt;</td>
      </tr>
      <tr>
        <td id="L617" class="blob-num js-line-number" data-line-number="617"></td>
        <td id="LC617" class="blob-code js-file-line">			&lt;<span class="pl-ent">h3</span>&gt;What&#39;s Next?&lt;/<span class="pl-ent">h3</span>&gt;</td>
      </tr>
      <tr>
        <td id="L618" class="blob-num js-line-number" data-line-number="618"></td>
        <td id="LC618" class="blob-code js-file-line">			&lt;<span class="pl-ent">p</span>&gt;You can download the latest version of the &lt;<span class="pl-ent">a</span> <span class="pl-e">href</span>=<span class="pl-s1"><span class="pl-pds">&quot;</span>http://aws.amazon.com/sdkforphp<span class="pl-pds">&quot;</span></span>&gt;&lt;<span class="pl-ent">strong</span>&gt;AWS SDK for PHP&lt;/<span class="pl-ent">strong</span>&gt;&lt;/<span class="pl-ent">a</span>&gt; and install it by &lt;<span class="pl-ent">a</span> <span class="pl-e">href</span>=<span class="pl-s1"><span class="pl-pds">&quot;</span>http://aws.amazon.com/articles/4261<span class="pl-pds">&quot;</span></span>&gt;following the instructions&lt;/<span class="pl-ent">a</span>&gt;. Also, check out our library of &lt;<span class="pl-ent">a</span> <span class="pl-e">href</span>=<span class="pl-s1"><span class="pl-pds">&quot;</span>http://aws.amazon.com/articles/4262<span class="pl-pds">&quot;</span></span>&gt;screencasts and tutorials&lt;/<span class="pl-ent">a</span>&gt;.&lt;/<span class="pl-ent">p</span>&gt;</td>
      </tr>
      <tr>
        <td id="L619" class="blob-num js-line-number" data-line-number="619"></td>
        <td id="LC619" class="blob-code js-file-line">			&lt;<span class="pl-ent">p</span>&gt;Take the time to read &lt;<span class="pl-ent">a</span> <span class="pl-e">href</span>=<span class="pl-s1"><span class="pl-pds">&quot;</span>http://aws.amazon.com/articles/4261<span class="pl-pds">&quot;</span></span>&gt;&quot;Getting Started&quot;&lt;/<span class="pl-ent">a</span>&gt; to make sure you&#39;re prepared to use the AWS SDK for PHP. No seriously, read it.&lt;/<span class="pl-ent">p</span>&gt;</td>
      </tr>
      <tr>
        <td id="L620" class="blob-num js-line-number" data-line-number="620"></td>
        <td id="LC620" class="blob-code js-file-line">		&lt;/<span class="pl-ent">div</span>&gt;</td>
      </tr>
      <tr>
        <td id="L621" class="blob-num js-line-number" data-line-number="621"></td>
        <td id="LC621" class="blob-code js-file-line">		<span class="pl-pse">&lt;?php</span><span class="pl-s2"> <span class="pl-k">elseif</span> (<span class="pl-vo">$compatiblity</span> <span class="pl-k">==</span> <span class="pl-c1">REQUIREMENTS_MIN_MET</span>): </span><span class="pl-pse"><span class="pl-s2">?</span>&gt;</span></td>
      </tr>
      <tr>
        <td id="L622" class="blob-num js-line-number" data-line-number="622"></td>
        <td id="LC622" class="blob-code js-file-line">		&lt;<span class="pl-ent">div</span> <span class="pl-e">class</span>=<span class="pl-s1"><span class="pl-pds">&quot;</span>chunk important ok<span class="pl-pds">&quot;</span></span>&gt;</td>
      </tr>
      <tr>
        <td id="L623" class="blob-num js-line-number" data-line-number="623"></td>
        <td id="LC623" class="blob-code js-file-line">			&lt;<span class="pl-ent">h3</span>&gt;Bottom Line: Yes, you can!&lt;/<span class="pl-ent">h3</span>&gt;</td>
      </tr>
      <tr>
        <td id="L624" class="blob-num js-line-number" data-line-number="624"></td>
        <td id="LC624" class="blob-code js-file-line">			&lt;<span class="pl-ent">p</span>&gt;Your PHP environment is ready to go! &lt;<span class="pl-ent">i</span>&gt;There are a couple of minor features that you won&#39;t be able to take advantage of, but nothing that&#39;s a show-stopper.&lt;/<span class="pl-ent">i</span>&gt;&lt;/<span class="pl-ent">p</span>&gt;</td>
      </tr>
      <tr>
        <td id="L625" class="blob-num js-line-number" data-line-number="625"></td>
        <td id="LC625" class="blob-code js-file-line">		&lt;/<span class="pl-ent">div</span>&gt;</td>
      </tr>
      <tr>
        <td id="L626" class="blob-num js-line-number" data-line-number="626"></td>
        <td id="LC626" class="blob-code js-file-line">		&lt;<span class="pl-ent">div</span> <span class="pl-e">class</span>=<span class="pl-s1"><span class="pl-pds">&quot;</span>chunk<span class="pl-pds">&quot;</span></span>&gt;</td>
      </tr>
      <tr>
        <td id="L627" class="blob-num js-line-number" data-line-number="627"></td>
        <td id="LC627" class="blob-code js-file-line">			&lt;<span class="pl-ent">h3</span>&gt;What&#39;s Next?&lt;/<span class="pl-ent">h3</span>&gt;</td>
      </tr>
      <tr>
        <td id="L628" class="blob-num js-line-number" data-line-number="628"></td>
        <td id="LC628" class="blob-code js-file-line">			&lt;<span class="pl-ent">p</span>&gt;You can download the latest version of the &lt;<span class="pl-ent">a</span> <span class="pl-e">href</span>=<span class="pl-s1"><span class="pl-pds">&quot;</span>http://aws.amazon.com/sdkforphp<span class="pl-pds">&quot;</span></span>&gt;&lt;<span class="pl-ent">strong</span>&gt;AWS SDK for PHP&lt;/<span class="pl-ent">strong</span>&gt;&lt;/<span class="pl-ent">a</span>&gt; and install it by &lt;<span class="pl-ent">a</span> <span class="pl-e">href</span>=<span class="pl-s1"><span class="pl-pds">&quot;</span>http://aws.amazon.com/articles/4261<span class="pl-pds">&quot;</span></span>&gt;following the instructions&lt;/<span class="pl-ent">a</span>&gt;. Also, check out our library of &lt;<span class="pl-ent">a</span> <span class="pl-e">href</span>=<span class="pl-s1"><span class="pl-pds">&quot;</span>http://aws.amazon.com/articles/4262<span class="pl-pds">&quot;</span></span>&gt;screencasts and tutorials&lt;/<span class="pl-ent">a</span>&gt;.&lt;/<span class="pl-ent">p</span>&gt;</td>
      </tr>
      <tr>
        <td id="L629" class="blob-num js-line-number" data-line-number="629"></td>
        <td id="LC629" class="blob-code js-file-line">			&lt;<span class="pl-ent">p</span>&gt;Take the time to read &lt;<span class="pl-ent">a</span> <span class="pl-e">href</span>=<span class="pl-s1"><span class="pl-pds">&quot;</span>http://aws.amazon.com/articles/4261<span class="pl-pds">&quot;</span></span>&gt;&quot;Getting Started&quot;&lt;/<span class="pl-ent">a</span>&gt; to make sure you&#39;re prepared to use the AWS SDK for PHP. No seriously, read it.&lt;/<span class="pl-ent">p</span>&gt;</td>
      </tr>
      <tr>
        <td id="L630" class="blob-num js-line-number" data-line-number="630"></td>
        <td id="LC630" class="blob-code js-file-line">		&lt;/<span class="pl-ent">div</span>&gt;</td>
      </tr>
      <tr>
        <td id="L631" class="blob-num js-line-number" data-line-number="631"></td>
        <td id="LC631" class="blob-code js-file-line">		<span class="pl-pse">&lt;?php</span><span class="pl-s2"> <span class="pl-k">else</span>: </span><span class="pl-pse"><span class="pl-s2">?</span>&gt;</span></td>
      </tr>
      <tr>
        <td id="L632" class="blob-num js-line-number" data-line-number="632"></td>
        <td id="LC632" class="blob-code js-file-line">		&lt;<span class="pl-ent">div</span> <span class="pl-e">class</span>=<span class="pl-s1"><span class="pl-pds">&quot;</span>chunk important error<span class="pl-pds">&quot;</span></span>&gt;</td>
      </tr>
      <tr>
        <td id="L633" class="blob-num js-line-number" data-line-number="633"></td>
        <td id="LC633" class="blob-code js-file-line">			&lt;<span class="pl-ent">h3</span>&gt;Bottom Line: We&#39;re sorry<span class="pl-c1">&amp;hellip;</span>&lt;/<span class="pl-ent">h3</span>&gt;</td>
      </tr>
      <tr>
        <td id="L634" class="blob-num js-line-number" data-line-number="634"></td>
        <td id="LC634" class="blob-code js-file-line">			&lt;<span class="pl-ent">p</span>&gt;Your PHP environment does not support the minimum requirements for the &lt;<span class="pl-ent">strong</span>&gt;AWS SDK for PHP&lt;/<span class="pl-ent">strong</span>&gt;.&lt;/<span class="pl-ent">p</span>&gt;</td>
      </tr>
      <tr>
        <td id="L635" class="blob-num js-line-number" data-line-number="635"></td>
        <td id="LC635" class="blob-code js-file-line">		&lt;/<span class="pl-ent">div</span>&gt;</td>
      </tr>
      <tr>
        <td id="L636" class="blob-num js-line-number" data-line-number="636"></td>
        <td id="LC636" class="blob-code js-file-line">		&lt;<span class="pl-ent">div</span> <span class="pl-e">class</span>=<span class="pl-s1"><span class="pl-pds">&quot;</span>chunk<span class="pl-pds">&quot;</span></span>&gt;</td>
      </tr>
      <tr>
        <td id="L637" class="blob-num js-line-number" data-line-number="637"></td>
        <td id="LC637" class="blob-code js-file-line">			&lt;<span class="pl-ent">h3</span>&gt;What&#39;s Next?&lt;/<span class="pl-ent">h3</span>&gt;</td>
      </tr>
      <tr>
        <td id="L638" class="blob-num js-line-number" data-line-number="638"></td>
        <td id="LC638" class="blob-code js-file-line">			&lt;<span class="pl-ent">p</span>&gt;If you&#39;re using a shared hosting plan, it may be a good idea to contact your web host and ask them to install a more recent version of PHP and relevant extensions.&lt;/<span class="pl-ent">p</span>&gt;</td>
      </tr>
      <tr>
        <td id="L639" class="blob-num js-line-number" data-line-number="639"></td>
        <td id="LC639" class="blob-code js-file-line">			&lt;<span class="pl-ent">p</span>&gt;If you have control over your PHP environment, we recommended that you upgrade your PHP environment. Check out the &quot;Set Up Your Environment&quot; section of the &lt;<span class="pl-ent">a</span> <span class="pl-e">href</span>=<span class="pl-s1"><span class="pl-pds">&quot;</span>http://aws.amazon.com/articles/4261<span class="pl-pds">&quot;</span></span>&gt;Getting Started Guide&lt;/<span class="pl-ent">a</span>&gt; for more information.&lt;/<span class="pl-ent">p</span>&gt;</td>
      </tr>
      <tr>
        <td id="L640" class="blob-num js-line-number" data-line-number="640"></td>
        <td id="LC640" class="blob-code js-file-line">		&lt;/<span class="pl-ent">div</span>&gt;</td>
      </tr>
      <tr>
        <td id="L641" class="blob-num js-line-number" data-line-number="641"></td>
        <td id="LC641" class="blob-code js-file-line">		<span class="pl-pse">&lt;?php</span><span class="pl-s2"> <span class="pl-k">endif</span>; </span><span class="pl-pse"><span class="pl-s2">?</span>&gt;</span></td>
      </tr>
      <tr>
        <td id="L642" class="blob-num js-line-number" data-line-number="642"></td>
        <td id="LC642" class="blob-code js-file-line">
</td>
      </tr>
      <tr>
        <td id="L643" class="blob-num js-line-number" data-line-number="643"></td>
        <td id="LC643" class="blob-code js-file-line">		<span class="pl-pse">&lt;?php</span><span class="pl-s2"> <span class="pl-k">if</span> (<span class="pl-vo">$compatiblity</span> <span class="pl-k">&gt;=</span> <span class="pl-c1">REQUIREMENTS_MIN_MET</span>): </span><span class="pl-pse"><span class="pl-s2">?</span>&gt;</span></td>
      </tr>
      <tr>
        <td id="L644" class="blob-num js-line-number" data-line-number="644"></td>
        <td id="LC644" class="blob-code js-file-line">		&lt;<span class="pl-ent">div</span> <span class="pl-e">class</span>=<span class="pl-s1"><span class="pl-pds">&quot;</span>chunk<span class="pl-pds">&quot;</span></span>&gt;</td>
      </tr>
      <tr>
        <td id="L645" class="blob-num js-line-number" data-line-number="645"></td>
        <td id="LC645" class="blob-code js-file-line">			&lt;<span class="pl-ent">h3</span>&gt;Recommended settings for config.inc.php&lt;/<span class="pl-ent">h3</span>&gt;</td>
      </tr>
      <tr>
        <td id="L646" class="blob-num js-line-number" data-line-number="646"></td>
        <td id="LC646" class="blob-code js-file-line">			&lt;<span class="pl-ent">p</span>&gt;Based on your particular server configuration, the following settings are recommended.&lt;/<span class="pl-ent">p</span>&gt;</td>
      </tr>
      <tr>
        <td id="L647" class="blob-num js-line-number" data-line-number="647"></td>
        <td id="LC647" class="blob-code js-file-line">			&lt;<span class="pl-ent">br</span>&gt;</td>
      </tr>
      <tr>
        <td id="L648" class="blob-num js-line-number" data-line-number="648"></td>
        <td id="LC648" class="blob-code js-file-line">			&lt;<span class="pl-ent">table</span> <span class="pl-e">cellpadding</span>=<span class="pl-s1"><span class="pl-pds">&quot;</span>0<span class="pl-pds">&quot;</span></span> <span class="pl-e">cellspacing</span>=<span class="pl-s1"><span class="pl-pds">&quot;</span>0<span class="pl-pds">&quot;</span></span> <span class="pl-e">border</span>=<span class="pl-s1"><span class="pl-pds">&quot;</span>0<span class="pl-pds">&quot;</span></span> <span class="pl-e">width</span>=<span class="pl-s1"><span class="pl-pds">&quot;</span>100%<span class="pl-pds">&quot;</span></span> <span class="pl-e">class</span>=<span class="pl-s1"><span class="pl-pds">&quot;</span>chart<span class="pl-pds">&quot;</span></span>&gt;</td>
      </tr>
      <tr>
        <td id="L649" class="blob-num js-line-number" data-line-number="649"></td>
        <td id="LC649" class="blob-code js-file-line">				&lt;<span class="pl-ent">thead</span>&gt;</td>
      </tr>
      <tr>
        <td id="L650" class="blob-num js-line-number" data-line-number="650"></td>
        <td id="LC650" class="blob-code js-file-line">					&lt;<span class="pl-ent">tr</span>&gt;</td>
      </tr>
      <tr>
        <td id="L651" class="blob-num js-line-number" data-line-number="651"></td>
        <td id="LC651" class="blob-code js-file-line">						&lt;<span class="pl-ent">th</span>&gt;Configuration Setting&lt;/<span class="pl-ent">th</span>&gt;</td>
      </tr>
      <tr>
        <td id="L652" class="blob-num js-line-number" data-line-number="652"></td>
        <td id="LC652" class="blob-code js-file-line">						&lt;<span class="pl-ent">th</span>&gt;Recommended Value&lt;/<span class="pl-ent">th</span>&gt;</td>
      </tr>
      <tr>
        <td id="L653" class="blob-num js-line-number" data-line-number="653"></td>
        <td id="LC653" class="blob-code js-file-line">					&lt;/<span class="pl-ent">tr</span>&gt;</td>
      </tr>
      <tr>
        <td id="L654" class="blob-num js-line-number" data-line-number="654"></td>
        <td id="LC654" class="blob-code js-file-line">				&lt;/<span class="pl-ent">thead</span>&gt;</td>
      </tr>
      <tr>
        <td id="L655" class="blob-num js-line-number" data-line-number="655"></td>
        <td id="LC655" class="blob-code js-file-line">				&lt;<span class="pl-ent">tbody</span>&gt;</td>
      </tr>
      <tr>
        <td id="L656" class="blob-num js-line-number" data-line-number="656"></td>
        <td id="LC656" class="blob-code js-file-line">					&lt;<span class="pl-ent">tr</span>&gt;</td>
      </tr>
      <tr>
        <td id="L657" class="blob-num js-line-number" data-line-number="657"></td>
        <td id="LC657" class="blob-code js-file-line">						&lt;<span class="pl-ent">td</span>&gt;&lt;<span class="pl-ent">code</span>&gt;default_cache_config&lt;/<span class="pl-ent">code</span>&gt;&lt;/<span class="pl-ent">td</span>&gt;</td>
      </tr>
      <tr>
        <td id="L658" class="blob-num js-line-number" data-line-number="658"></td>
        <td id="LC658" class="blob-code js-file-line">						<span class="pl-pse">&lt;?php</span><span class="pl-s2"> <span class="pl-k">if</span> (<span class="pl-vo">$apc_ok</span>): </span><span class="pl-pse"><span class="pl-s2">?</span>&gt;</span></td>
      </tr>
      <tr>
        <td id="L659" class="blob-num js-line-number" data-line-number="659"></td>
        <td id="LC659" class="blob-code js-file-line">						&lt;<span class="pl-ent">td</span>&gt;&lt;<span class="pl-ent">code</span>&gt;apc&lt;/<span class="pl-ent">code</span>&gt;&lt;/<span class="pl-ent">td</span>&gt;</td>
      </tr>
      <tr>
        <td id="L660" class="blob-num js-line-number" data-line-number="660"></td>
        <td id="LC660" class="blob-code js-file-line">						<span class="pl-pse">&lt;?php</span><span class="pl-s2"> <span class="pl-k">elseif</span> (<span class="pl-vo">$xcache_ok</span>): </span><span class="pl-pse"><span class="pl-s2">?</span>&gt;</span></td>
      </tr>
      <tr>
        <td id="L661" class="blob-num js-line-number" data-line-number="661"></td>
        <td id="LC661" class="blob-code js-file-line">						&lt;<span class="pl-ent">td</span>&gt;&lt;<span class="pl-ent">code</span>&gt;xcache&lt;/<span class="pl-ent">code</span>&gt;&lt;/<span class="pl-ent">td</span>&gt;</td>
      </tr>
      <tr>
        <td id="L662" class="blob-num js-line-number" data-line-number="662"></td>
        <td id="LC662" class="blob-code js-file-line">						<span class="pl-pse">&lt;?php</span><span class="pl-s2"> <span class="pl-k">elseif</span> (<span class="pl-vo">$file_ok</span>): </span><span class="pl-pse"><span class="pl-s2">?</span>&gt;</span></td>
      </tr>
      <tr>
        <td id="L663" class="blob-num js-line-number" data-line-number="663"></td>
        <td id="LC663" class="blob-code js-file-line">						&lt;<span class="pl-ent">td</span>&gt;Any valid, server-writable file system path&lt;/<span class="pl-ent">td</span>&gt;</td>
      </tr>
      <tr>
        <td id="L664" class="blob-num js-line-number" data-line-number="664"></td>
        <td id="LC664" class="blob-code js-file-line">						<span class="pl-pse">&lt;?php</span><span class="pl-s2"> <span class="pl-k">endif</span>; </span><span class="pl-pse"><span class="pl-s2">?</span>&gt;</span></td>
      </tr>
      <tr>
        <td id="L665" class="blob-num js-line-number" data-line-number="665"></td>
        <td id="LC665" class="blob-code js-file-line">					&lt;/<span class="pl-ent">tr</span>&gt;</td>
      </tr>
      <tr>
        <td id="L666" class="blob-num js-line-number" data-line-number="666"></td>
        <td id="LC666" class="blob-code js-file-line">					&lt;<span class="pl-ent">tr</span>&gt;</td>
      </tr>
      <tr>
        <td id="L667" class="blob-num js-line-number" data-line-number="667"></td>
        <td id="LC667" class="blob-code js-file-line">						&lt;<span class="pl-ent">td</span>&gt;&lt;<span class="pl-ent">code</span>&gt;certificate_authority&lt;/<span class="pl-ent">code</span>&gt;&lt;/<span class="pl-ent">td</span>&gt;</td>
      </tr>
      <tr>
        <td id="L668" class="blob-num js-line-number" data-line-number="668"></td>
        <td id="LC668" class="blob-code js-file-line">						<span class="pl-pse">&lt;?php</span><span class="pl-s2"> <span class="pl-k">if</span> (is_windows()): </span><span class="pl-pse"><span class="pl-s2">?</span>&gt;</span></td>
      </tr>
      <tr>
        <td id="L669" class="blob-num js-line-number" data-line-number="669"></td>
        <td id="LC669" class="blob-code js-file-line">						&lt;<span class="pl-ent">td</span> <span class="pl-e">id</span>=<span class="pl-s1"><span class="pl-pds">&quot;</span>ssl_check<span class="pl-pds">&quot;</span></span>&gt;&lt;<span class="pl-ent">code</span>&gt;true&lt;/<span class="pl-ent">code</span>&gt;&lt;/<span class="pl-ent">td</span>&gt;</td>
      </tr>
      <tr>
        <td id="L670" class="blob-num js-line-number" data-line-number="670"></td>
        <td id="LC670" class="blob-code js-file-line">						<span class="pl-pse">&lt;?php</span><span class="pl-s2"> <span class="pl-k">else</span>: </span><span class="pl-pse"><span class="pl-s2">?</span>&gt;</span></td>
      </tr>
      <tr>
        <td id="L671" class="blob-num js-line-number" data-line-number="671"></td>
        <td id="LC671" class="blob-code js-file-line">						&lt;<span class="pl-ent">td</span> <span class="pl-e">id</span>=<span class="pl-s1"><span class="pl-pds">&quot;</span>ssl_check<span class="pl-pds">&quot;</span></span>&gt;&lt;<span class="pl-ent">img</span> <span class="pl-e">src</span>=<span class="pl-s1"><span class="pl-pds">&quot;</span><span class="pl-pse">&lt;?php</span><span class="pl-s2"> <span class="pl-s3">echo</span> <span class="pl-s3">pathinfo</span>(<span class="pl-c1">__FILE__</span>, <span class="pl-sc">PATHINFO_BASENAME</span>); </span><span class="pl-pse"><span class="pl-s2">?</span>&gt;</span>?loader<span class="pl-pds">&quot;</span></span> <span class="pl-e">alt</span>=<span class="pl-s1"><span class="pl-pds">&quot;</span>Loading...<span class="pl-pds">&quot;</span></span>&gt;&lt;/<span class="pl-ent">td</span>&gt;</td>
      </tr>
      <tr>
        <td id="L672" class="blob-num js-line-number" data-line-number="672"></td>
        <td id="LC672" class="blob-code js-file-line">						<span class="pl-pse">&lt;?php</span><span class="pl-s2"> <span class="pl-k">endif</span>; </span><span class="pl-pse"><span class="pl-s2">?</span>&gt;</span></td>
      </tr>
      <tr>
        <td id="L673" class="blob-num js-line-number" data-line-number="673"></td>
        <td id="LC673" class="blob-code js-file-line">					&lt;/<span class="pl-ent">tr</span>&gt;</td>
      </tr>
      <tr>
        <td id="L674" class="blob-num js-line-number" data-line-number="674"></td>
        <td id="LC674" class="blob-code js-file-line">				&lt;/<span class="pl-ent">tbody</span>&gt;</td>
      </tr>
      <tr>
        <td id="L675" class="blob-num js-line-number" data-line-number="675"></td>
        <td id="LC675" class="blob-code js-file-line">			&lt;/<span class="pl-ent">table</span>&gt;</td>
      </tr>
      <tr>
        <td id="L676" class="blob-num js-line-number" data-line-number="676"></td>
        <td id="LC676" class="blob-code js-file-line">			&lt;<span class="pl-ent">br</span>&gt;</td>
      </tr>
      <tr>
        <td id="L677" class="blob-num js-line-number" data-line-number="677"></td>
        <td id="LC677" class="blob-code js-file-line">		&lt;/<span class="pl-ent">div</span>&gt;</td>
      </tr>
      <tr>
        <td id="L678" class="blob-num js-line-number" data-line-number="678"></td>
        <td id="LC678" class="blob-code js-file-line">		<span class="pl-pse">&lt;?php</span><span class="pl-s2"> <span class="pl-k">endif</span>; </span><span class="pl-pse"><span class="pl-s2">?</span>&gt;</span></td>
      </tr>
      <tr>
        <td id="L679" class="blob-num js-line-number" data-line-number="679"></td>
        <td id="LC679" class="blob-code js-file-line">
</td>
      </tr>
      <tr>
        <td id="L680" class="blob-num js-line-number" data-line-number="680"></td>
        <td id="LC680" class="blob-code js-file-line">		&lt;<span class="pl-ent">div</span> <span class="pl-e">class</span>=<span class="pl-s1"><span class="pl-pds">&quot;</span>chunk<span class="pl-pds">&quot;</span></span>&gt;</td>
      </tr>
      <tr>
        <td id="L681" class="blob-num js-line-number" data-line-number="681"></td>
        <td id="LC681" class="blob-code js-file-line">			&lt;<span class="pl-ent">h3</span>&gt;Give me the details!&lt;/<span class="pl-ent">h3</span>&gt;</td>
      </tr>
      <tr>
        <td id="L682" class="blob-num js-line-number" data-line-number="682"></td>
        <td id="LC682" class="blob-code js-file-line">			<span class="pl-pse">&lt;?php</span><span class="pl-s2"> <span class="pl-k">if</span> (<span class="pl-vo">$compatiblity</span> <span class="pl-k">&gt;=</span> <span class="pl-c1">REQUIREMENTS_MIN_MET</span>): </span><span class="pl-pse"><span class="pl-s2">?</span>&gt;</span></td>
      </tr>
      <tr>
        <td id="L683" class="blob-num js-line-number" data-line-number="683"></td>
        <td id="LC683" class="blob-code js-file-line">			&lt;<span class="pl-ent">ol</span>&gt;</td>
      </tr>
      <tr>
        <td id="L684" class="blob-num js-line-number" data-line-number="684"></td>
        <td id="LC684" class="blob-code js-file-line">				&lt;<span class="pl-ent">li</span>&gt;&lt;<span class="pl-ent">em</span>&gt;Your environment meets the minimum requirements for using the &lt;<span class="pl-ent">strong</span>&gt;AWS SDK for PHP&lt;/<span class="pl-ent">strong</span>&gt;!&lt;/<span class="pl-ent">em</span>&gt;&lt;/<span class="pl-ent">li</span>&gt;</td>
      </tr>
      <tr>
        <td id="L685" class="blob-num js-line-number" data-line-number="685"></td>
        <td id="LC685" class="blob-code js-file-line">
</td>
      </tr>
      <tr>
        <td id="L686" class="blob-num js-line-number" data-line-number="686"></td>
        <td id="LC686" class="blob-code js-file-line">				<span class="pl-pse">&lt;?php</span><span class="pl-s2"> <span class="pl-k">if</span> (<span class="pl-s3">version_compare</span>(<span class="pl-sc">PHP_VERSION</span>, <span class="pl-s1"><span class="pl-pds">&#39;</span>5.3.0<span class="pl-pds">&#39;</span></span>) <span class="pl-k">&lt;</span> <span class="pl-c1">0</span>): </span><span class="pl-pse"><span class="pl-s2">?</span>&gt;</span></td>
      </tr>
      <tr>
        <td id="L687" class="blob-num js-line-number" data-line-number="687"></td>
        <td id="LC687" class="blob-code js-file-line">				&lt;<span class="pl-ent">li</span>&gt;You&#39;re still running &lt;<span class="pl-ent">strong</span>&gt;PHP <span class="pl-pse">&lt;?php</span><span class="pl-s2"> <span class="pl-s3">echo</span> <span class="pl-sc">PHP_VERSION</span>; </span><span class="pl-pse"><span class="pl-s2">?</span>&gt;</span>&lt;/<span class="pl-ent">strong</span>&gt;. The PHP 5.2 family is no longer supported by the PHP team, and future versions of the AWS SDK for PHP will &lt;<span class="pl-ent">i</span>&gt;require&lt;/<span class="pl-ent">i</span>&gt; PHP 5.3 or newer.&lt;/<span class="pl-ent">li</span>&gt;</td>
      </tr>
      <tr>
        <td id="L688" class="blob-num js-line-number" data-line-number="688"></td>
        <td id="LC688" class="blob-code js-file-line">				<span class="pl-pse">&lt;?php</span><span class="pl-s2"> <span class="pl-k">endif</span>; </span><span class="pl-pse"><span class="pl-s2">?</span>&gt;</span></td>
      </tr>
      <tr>
        <td id="L689" class="blob-num js-line-number" data-line-number="689"></td>
        <td id="LC689" class="blob-code js-file-line">
</td>
      </tr>
      <tr>
        <td id="L690" class="blob-num js-line-number" data-line-number="690"></td>
        <td id="LC690" class="blob-code js-file-line">				<span class="pl-pse">&lt;?php</span><span class="pl-s2"> <span class="pl-k">if</span> (<span class="pl-vo">$openssl_ok</span>): </span><span class="pl-pse"><span class="pl-s2">?</span>&gt;</span></td>
      </tr>
      <tr>
        <td id="L691" class="blob-num js-line-number" data-line-number="691"></td>
        <td id="LC691" class="blob-code js-file-line">				&lt;<span class="pl-ent">li</span>&gt;The &lt;<span class="pl-ent">a</span> <span class="pl-e">href</span>=<span class="pl-s1"><span class="pl-pds">&quot;</span>http://php.net/openssl<span class="pl-pds">&quot;</span></span>&gt;OpenSSL&lt;/<span class="pl-ent">a</span>&gt; extension is installed. This will allow you to use &lt;<span class="pl-ent">a</span> <span class="pl-e">href</span>=<span class="pl-s1"><span class="pl-pds">&quot;</span>http://docs.amazonwebservices.com/AmazonCloudFront/latest/DeveloperGuide/PrivateContent.html<span class="pl-pds">&quot;</span></span>&gt;CloudFront Private URLs&lt;/<span class="pl-ent">a</span>&gt; and decrypt Microsoft<span class="pl-c1">&amp;reg;</span> Windows<span class="pl-c1">&amp;reg;</span> instance passwords.&lt;/<span class="pl-ent">li</span>&gt;</td>
      </tr>
      <tr>
        <td id="L692" class="blob-num js-line-number" data-line-number="692"></td>
        <td id="LC692" class="blob-code js-file-line">				<span class="pl-pse">&lt;?php</span><span class="pl-s2"> <span class="pl-k">endif</span>; </span><span class="pl-pse"><span class="pl-s2">?</span>&gt;</span></td>
      </tr>
      <tr>
        <td id="L693" class="blob-num js-line-number" data-line-number="693"></td>
        <td id="LC693" class="blob-code js-file-line">
</td>
      </tr>
      <tr>
        <td id="L694" class="blob-num js-line-number" data-line-number="694"></td>
        <td id="LC694" class="blob-code js-file-line">				<span class="pl-pse">&lt;?php</span><span class="pl-s2"> <span class="pl-k">if</span> (<span class="pl-vo">$zlib_ok</span>): </span><span class="pl-pse"><span class="pl-s2">?</span>&gt;</span></td>
      </tr>
      <tr>
        <td id="L695" class="blob-num js-line-number" data-line-number="695"></td>
        <td id="LC695" class="blob-code js-file-line">				&lt;<span class="pl-ent">li</span>&gt;The &lt;<span class="pl-ent">a</span> <span class="pl-e">href</span>=<span class="pl-s1"><span class="pl-pds">&quot;</span>http://php.net/zlib<span class="pl-pds">&quot;</span></span>&gt;Zlib&lt;/<span class="pl-ent">a</span>&gt; extension is installed. The SDK will request gzipped data whenever possible.&lt;/<span class="pl-ent">li</span>&gt;</td>
      </tr>
      <tr>
        <td id="L696" class="blob-num js-line-number" data-line-number="696"></td>
        <td id="LC696" class="blob-code js-file-line">				<span class="pl-pse">&lt;?php</span><span class="pl-s2"> <span class="pl-k">endif</span>; </span><span class="pl-pse"><span class="pl-s2">?</span>&gt;</span></td>
      </tr>
      <tr>
        <td id="L697" class="blob-num js-line-number" data-line-number="697"></td>
        <td id="LC697" class="blob-code js-file-line">
</td>
      </tr>
      <tr>
        <td id="L698" class="blob-num js-line-number" data-line-number="698"></td>
        <td id="LC698" class="blob-code js-file-line">				<span class="pl-pse">&lt;?php</span><span class="pl-s2"> <span class="pl-k">if</span> (<span class="pl-k">!</span><span class="pl-vo">$int64_ok</span>): </span><span class="pl-pse"><span class="pl-s2">?</span>&gt;</span></td>
      </tr>
      <tr>
        <td id="L699" class="blob-num js-line-number" data-line-number="699"></td>
        <td id="LC699" class="blob-code js-file-line">				&lt;<span class="pl-ent">li</span>&gt;You&#39;re running on a &lt;<span class="pl-ent">strong</span>&gt;32-bit&lt;/<span class="pl-ent">strong</span>&gt; system. This means that PHP does not correctly handle files larger than 2GB (this is a &lt;<span class="pl-ent">a</span> <span class="pl-e">href</span>=<span class="pl-s1"><span class="pl-pds">&quot;</span>http://www.google.com/search?q=php+2gb+32-bit<span class="pl-pds">&quot;</span></span>&gt;well-known PHP issue&lt;/<span class="pl-ent">a</span>&gt;). For more information, please see: &lt;<span class="pl-ent">a</span> <span class="pl-e">href</span>=<span class="pl-s1"><span class="pl-pds">&quot;</span>http://docs.php.net/manual/en/function.filesize.php#refsect1-function.filesize-returnvalues<span class="pl-pds">&quot;</span></span>&gt;PHP filesize: Return values&lt;/<span class="pl-ent">a</span>&gt;.&lt;/<span class="pl-ent">li</span>&gt;</td>
      </tr>
      <tr>
        <td id="L700" class="blob-num js-line-number" data-line-number="700"></td>
        <td id="LC700" class="blob-code js-file-line">				<span class="pl-pse">&lt;?php</span><span class="pl-s2"> <span class="pl-k">if</span> (is_windows()): </span><span class="pl-pse"><span class="pl-s2">?</span>&gt;</span></td>
      </tr>
      <tr>
        <td id="L701" class="blob-num js-line-number" data-line-number="701"></td>
        <td id="LC701" class="blob-code js-file-line">				&lt;<span class="pl-ent">li</span> <span class="pl-e">id</span>=<span class="pl-s1"><span class="pl-pds">&quot;</span>win64<span class="pl-pds">&quot;</span></span>&gt;&lt;<span class="pl-ent">em</span>&gt;Note that PHP on Microsoft® Windows® &lt;<span class="pl-ent">a</span> <span class="pl-e">href</span>=<span class="pl-s1"><span class="pl-pds">&quot;</span>http://j.mp/php64win<span class="pl-pds">&quot;</span></span>&gt;does not support 64-bit integers at all&lt;/<span class="pl-ent">a</span>&gt;, even if both the hardware and PHP are 64-bit.&lt;/<span class="pl-ent">em</span>&gt;&lt;/<span class="pl-ent">li</span>&gt;</td>
      </tr>
      <tr>
        <td id="L702" class="blob-num js-line-number" data-line-number="702"></td>
        <td id="LC702" class="blob-code js-file-line">				<span class="pl-pse">&lt;?php</span><span class="pl-s2"> <span class="pl-k">endif</span>; </span><span class="pl-pse"><span class="pl-s2">?</span>&gt;</span></td>
      </tr>
      <tr>
        <td id="L703" class="blob-num js-line-number" data-line-number="703"></td>
        <td id="LC703" class="blob-code js-file-line">				<span class="pl-pse">&lt;?php</span><span class="pl-s2"> <span class="pl-k">endif</span>; </span><span class="pl-pse"><span class="pl-s2">?</span>&gt;</span></td>
      </tr>
      <tr>
        <td id="L704" class="blob-num js-line-number" data-line-number="704"></td>
        <td id="LC704" class="blob-code js-file-line">
</td>
      </tr>
      <tr>
        <td id="L705" class="blob-num js-line-number" data-line-number="705"></td>
        <td id="LC705" class="blob-code js-file-line">				<span class="pl-pse">&lt;?php</span><span class="pl-s2"> <span class="pl-k">if</span> (<span class="pl-vo">$ini_open_basedir</span> <span class="pl-k">||</span> <span class="pl-vo">$ini_safe_mode</span>): </span><span class="pl-pse"><span class="pl-s2">?</span>&gt;</span></td>
      </tr>
      <tr>
        <td id="L706" class="blob-num js-line-number" data-line-number="706"></td>
        <td id="LC706" class="blob-code js-file-line">				&lt;<span class="pl-ent">li</span>&gt;You have &lt;<span class="pl-ent">a</span> <span class="pl-e">href</span>=<span class="pl-s1"><span class="pl-pds">&quot;</span>http://php.net/open_basedir<span class="pl-pds">&quot;</span></span>&gt;open_basedir&lt;/<span class="pl-ent">a</span>&gt; or &lt;<span class="pl-ent">a</span> <span class="pl-e">href</span>=<span class="pl-s1"><span class="pl-pds">&quot;</span>http://php.net/safe_mode<span class="pl-pds">&quot;</span></span>&gt;safe_mode&lt;/<span class="pl-ent">a</span>&gt; enabled in your &lt;<span class="pl-ent">code</span>&gt;php.ini&lt;/<span class="pl-ent">code</span>&gt; file. Sometimes PHP behaves strangely when these settings are enabled. Disable them if you can.&lt;/<span class="pl-ent">li</span>&gt;</td>
      </tr>
      <tr>
        <td id="L707" class="blob-num js-line-number" data-line-number="707"></td>
        <td id="LC707" class="blob-code js-file-line">				<span class="pl-pse">&lt;?php</span><span class="pl-s2"> <span class="pl-k">endif</span>; </span><span class="pl-pse"><span class="pl-s2">?</span>&gt;</span></td>
      </tr>
      <tr>
        <td id="L708" class="blob-num js-line-number" data-line-number="708"></td>
        <td id="LC708" class="blob-code js-file-line">
</td>
      </tr>
      <tr>
        <td id="L709" class="blob-num js-line-number" data-line-number="709"></td>
        <td id="LC709" class="blob-code js-file-line">				<span class="pl-pse">&lt;?php</span><span class="pl-s2"> <span class="pl-k">if</span> (<span class="pl-k">!</span><span class="pl-vo">$ini_zend_enable_gc</span>): </span><span class="pl-pse"><span class="pl-s2">?</span>&gt;</span></td>
      </tr>
      <tr>
        <td id="L710" class="blob-num js-line-number" data-line-number="710"></td>
        <td id="LC710" class="blob-code js-file-line">				&lt;<span class="pl-ent">li</span>&gt;The PHP garbage collector (available in PHP 5.3+) is not enabled in your &lt;<span class="pl-ent">code</span>&gt;php.ini&lt;/<span class="pl-ent">code</span>&gt; file. Enabling &lt;<span class="pl-ent">a</span> <span class="pl-e">href</span>=<span class="pl-s1"><span class="pl-pds">&quot;</span>http://php.net/zend.enable_gc<span class="pl-pds">&quot;</span></span>&gt;zend.enable_gc&lt;/<span class="pl-ent">a</span>&gt; will provide better memory management in the PHP core.&lt;/<span class="pl-ent">li</span>&gt;</td>
      </tr>
      <tr>
        <td id="L711" class="blob-num js-line-number" data-line-number="711"></td>
        <td id="LC711" class="blob-code js-file-line">				<span class="pl-pse">&lt;?php</span><span class="pl-s2"> <span class="pl-k">endif</span>; </span><span class="pl-pse"><span class="pl-s2">?</span>&gt;</span></td>
      </tr>
      <tr>
        <td id="L712" class="blob-num js-line-number" data-line-number="712"></td>
        <td id="LC712" class="blob-code js-file-line">
</td>
      </tr>
      <tr>
        <td id="L713" class="blob-num js-line-number" data-line-number="713"></td>
        <td id="LC713" class="blob-code js-file-line">				<span class="pl-pse">&lt;?php</span><span class="pl-s2"></span></td>
      </tr>
      <tr>
        <td id="L714" class="blob-num js-line-number" data-line-number="714"></td>
        <td id="LC714" class="blob-code js-file-line"><span class="pl-s2">				<span class="pl-vo">$storage_types</span> <span class="pl-k">=</span> <span class="pl-s3">array</span>();</span></td>
      </tr>
      <tr>
        <td id="L715" class="blob-num js-line-number" data-line-number="715"></td>
        <td id="LC715" class="blob-code js-file-line"><span class="pl-s2">				<span class="pl-k">if</span> (<span class="pl-vo">$file_ok</span>) { <span class="pl-vo">$storage_types</span>[] <span class="pl-k">=</span> <span class="pl-s1"><span class="pl-pds">&#39;</span>&lt;a href=&quot;http://php.net/file_put_contents&quot;&gt;The file system&lt;/a&gt;<span class="pl-pds">&#39;</span></span>; }</span></td>
      </tr>
      <tr>
        <td id="L716" class="blob-num js-line-number" data-line-number="716"></td>
        <td id="LC716" class="blob-code js-file-line"><span class="pl-s2">				<span class="pl-k">if</span> (<span class="pl-vo">$apc_ok</span>) { <span class="pl-vo">$storage_types</span>[] <span class="pl-k">=</span> <span class="pl-s1"><span class="pl-pds">&#39;</span>&lt;a href=&quot;http://php.net/apc&quot;&gt;APC&lt;/a&gt;<span class="pl-pds">&#39;</span></span>; }</span></td>
      </tr>
      <tr>
        <td id="L717" class="blob-num js-line-number" data-line-number="717"></td>
        <td id="LC717" class="blob-code js-file-line"><span class="pl-s2">				<span class="pl-k">if</span> (<span class="pl-vo">$xcache_ok</span>) { <span class="pl-vo">$storage_types</span>[] <span class="pl-k">=</span> <span class="pl-s1"><span class="pl-pds">&#39;</span>&lt;a href=&quot;http://xcache.lighttpd.net&quot;&gt;XCache&lt;/a&gt;<span class="pl-pds">&#39;</span></span>; }</span></td>
      </tr>
      <tr>
        <td id="L718" class="blob-num js-line-number" data-line-number="718"></td>
        <td id="LC718" class="blob-code js-file-line"><span class="pl-s2">				<span class="pl-k">if</span> (<span class="pl-vo">$sqlite_ok</span> <span class="pl-k">&amp;&amp;</span> <span class="pl-vo">$sqlite3_ok</span>) { <span class="pl-vo">$storage_types</span>[] <span class="pl-k">=</span> <span class="pl-s1"><span class="pl-pds">&#39;</span>&lt;a href=&quot;http://php.net/sqlite3&quot;&gt;SQLite 3&lt;/a&gt;<span class="pl-pds">&#39;</span></span>; }</span></td>
      </tr>
      <tr>
        <td id="L719" class="blob-num js-line-number" data-line-number="719"></td>
        <td id="LC719" class="blob-code js-file-line"><span class="pl-s2">				<span class="pl-k">elseif</span> (<span class="pl-vo">$sqlite_ok</span> <span class="pl-k">&amp;&amp;</span> <span class="pl-vo">$sqlite2_ok</span>) { <span class="pl-vo">$storage_types</span>[] <span class="pl-k">=</span> <span class="pl-s1"><span class="pl-pds">&#39;</span>&lt;a href=&quot;http://php.net/sqlite&quot;&gt;SQLite 2&lt;/a&gt;<span class="pl-pds">&#39;</span></span>; }</span></td>
      </tr>
      <tr>
        <td id="L720" class="blob-num js-line-number" data-line-number="720"></td>
        <td id="LC720" class="blob-code js-file-line"><span class="pl-s2">				<span class="pl-k">if</span> (<span class="pl-vo">$memcached_ok</span>) { <span class="pl-vo">$storage_types</span>[] <span class="pl-k">=</span> <span class="pl-s1"><span class="pl-pds">&#39;</span>&lt;a href=&quot;http://php.net/memcached&quot;&gt;Memcached&lt;/a&gt;<span class="pl-pds">&#39;</span></span>; }</span></td>
      </tr>
      <tr>
        <td id="L721" class="blob-num js-line-number" data-line-number="721"></td>
        <td id="LC721" class="blob-code js-file-line"><span class="pl-s2">				<span class="pl-k">elseif</span> (<span class="pl-vo">$memcache_ok</span>) { <span class="pl-vo">$storage_types</span>[] <span class="pl-k">=</span> <span class="pl-s1"><span class="pl-pds">&#39;</span>&lt;a href=&quot;http://php.net/memcache&quot;&gt;Memcache&lt;/a&gt;<span class="pl-pds">&#39;</span></span>; }</span></td>
      </tr>
      <tr>
        <td id="L722" class="blob-num js-line-number" data-line-number="722"></td>
        <td id="LC722" class="blob-code js-file-line"><span class="pl-s2">				</span><span class="pl-pse"><span class="pl-s2">?</span>&gt;</span></td>
      </tr>
      <tr>
        <td id="L723" class="blob-num js-line-number" data-line-number="723"></td>
        <td id="LC723" class="blob-code js-file-line">				&lt;<span class="pl-ent">li</span>&gt;Storage types available for response caching: <span class="pl-pse">&lt;?php</span><span class="pl-s2"> <span class="pl-s3">echo</span> <span class="pl-s3">implode</span>(<span class="pl-s1"><span class="pl-pds">&#39;</span>, <span class="pl-pds">&#39;</span></span>, <span class="pl-vo">$storage_types</span>); </span><span class="pl-pse"><span class="pl-s2">?</span>&gt;</span>&lt;/<span class="pl-ent">li</span>&gt;</td>
      </tr>
      <tr>
        <td id="L724" class="blob-num js-line-number" data-line-number="724"></td>
        <td id="LC724" class="blob-code js-file-line">			&lt;/<span class="pl-ent">ol</span>&gt;</td>
      </tr>
      <tr>
        <td id="L725" class="blob-num js-line-number" data-line-number="725"></td>
        <td id="LC725" class="blob-code js-file-line">
</td>
      </tr>
      <tr>
        <td id="L726" class="blob-num js-line-number" data-line-number="726"></td>
        <td id="LC726" class="blob-code js-file-line">				<span class="pl-pse">&lt;?php</span><span class="pl-s2"> <span class="pl-k">if</span> (<span class="pl-k">!</span><span class="pl-vo">$openssl_ok</span> <span class="pl-k">&amp;&amp;</span> <span class="pl-k">!</span><span class="pl-vo">$zlib_ok</span>): </span><span class="pl-pse"><span class="pl-s2">?</span>&gt;</span></td>
      </tr>
      <tr>
        <td id="L727" class="blob-num js-line-number" data-line-number="727"></td>
        <td id="LC727" class="blob-code js-file-line">				&lt;<span class="pl-ent">p</span> <span class="pl-e">class</span>=<span class="pl-s1"><span class="pl-pds">&quot;</span>footnote<span class="pl-pds">&quot;</span></span>&gt;&lt;<span class="pl-ent">strong</span>&gt;NOTE:&lt;/<span class="pl-ent">strong</span>&gt; You&#39;re missing the &lt;<span class="pl-ent">a</span> <span class="pl-e">href</span>=<span class="pl-s1"><span class="pl-pds">&quot;</span>http://php.net/openssl<span class="pl-pds">&quot;</span></span>&gt;OpenSSL&lt;/<span class="pl-ent">a</span>&gt; extension, which means that you won&#39;t be able to take advantage of &lt;<span class="pl-ent">a</span> <span class="pl-e">href</span>=<span class="pl-s1"><span class="pl-pds">&quot;</span>http://docs.amazonwebservices.com/AmazonCloudFront/latest/DeveloperGuide/PrivateContent.html<span class="pl-pds">&quot;</span></span>&gt;CloudFront Private URLs&lt;/<span class="pl-ent">a</span>&gt; or decrypt Microsoft<span class="pl-c1">&amp;reg;</span> Windows<span class="pl-c1">&amp;reg;</span> instance passwords. You&#39;re also missing the &lt;<span class="pl-ent">a</span> <span class="pl-e">href</span>=<span class="pl-s1"><span class="pl-pds">&quot;</span>http://php.net/zlib<span class="pl-pds">&quot;</span></span>&gt;Zlib&lt;/<span class="pl-ent">a</span>&gt; extension, which means that the SDK will be unable to request gzipped data from Amazon and you won&#39;t be able to take advantage of compression with the &lt;<span class="pl-ent">i</span>&gt;response caching&lt;/<span class="pl-ent">i</span>&gt; feature.&lt;/<span class="pl-ent">p</span>&gt;</td>
      </tr>
      <tr>
        <td id="L728" class="blob-num js-line-number" data-line-number="728"></td>
        <td id="LC728" class="blob-code js-file-line">				<span class="pl-pse">&lt;?php</span><span class="pl-s2"> <span class="pl-k">elseif</span> (<span class="pl-k">!</span><span class="pl-vo">$zlib_ok</span>): </span><span class="pl-pse"><span class="pl-s2">?</span>&gt;</span></td>
      </tr>
      <tr>
        <td id="L729" class="blob-num js-line-number" data-line-number="729"></td>
        <td id="LC729" class="blob-code js-file-line">				&lt;<span class="pl-ent">p</span> <span class="pl-e">class</span>=<span class="pl-s1"><span class="pl-pds">&quot;</span>footnote<span class="pl-pds">&quot;</span></span>&gt;&lt;<span class="pl-ent">strong</span>&gt;NOTE:&lt;/<span class="pl-ent">strong</span>&gt; You&#39;re missing the &lt;<span class="pl-ent">a</span> <span class="pl-e">href</span>=<span class="pl-s1"><span class="pl-pds">&quot;</span>http://php.net/zlib<span class="pl-pds">&quot;</span></span>&gt;Zlib&lt;/<span class="pl-ent">a</span>&gt; extension, which means that the SDK will be unable to request gzipped data from Amazon and you won&#39;t be able to take advantage of compression with the &lt;<span class="pl-ent">i</span>&gt;response caching&lt;/<span class="pl-ent">i</span>&gt; feature.&lt;/<span class="pl-ent">p</span>&gt;</td>
      </tr>
      <tr>
        <td id="L730" class="blob-num js-line-number" data-line-number="730"></td>
        <td id="LC730" class="blob-code js-file-line">				<span class="pl-pse">&lt;?php</span><span class="pl-s2"> <span class="pl-k">elseif</span> (<span class="pl-k">!</span><span class="pl-vo">$openssl_ok</span>): </span><span class="pl-pse"><span class="pl-s2">?</span>&gt;</span></td>
      </tr>
      <tr>
        <td id="L731" class="blob-num js-line-number" data-line-number="731"></td>
        <td id="LC731" class="blob-code js-file-line">				&lt;<span class="pl-ent">p</span> <span class="pl-e">class</span>=<span class="pl-s1"><span class="pl-pds">&quot;</span>footnote<span class="pl-pds">&quot;</span></span>&gt;&lt;<span class="pl-ent">strong</span>&gt;NOTE:&lt;/<span class="pl-ent">strong</span>&gt; You&#39;re missing the &lt;<span class="pl-ent">a</span> <span class="pl-e">href</span>=<span class="pl-s1"><span class="pl-pds">&quot;</span>http://php.net/openssl<span class="pl-pds">&quot;</span></span>&gt;OpenSSL&lt;/<span class="pl-ent">a</span>&gt; extension, which means that you won&#39;t be able to take advantage of &lt;<span class="pl-ent">a</span> <span class="pl-e">href</span>=<span class="pl-s1"><span class="pl-pds">&quot;</span>http://docs.amazonwebservices.com/AmazonCloudFront/latest/DeveloperGuide/PrivateContent.html<span class="pl-pds">&quot;</span></span>&gt;CloudFront Private URLs&lt;/<span class="pl-ent">a</span>&gt; or decrypt Microsoft<span class="pl-c1">&amp;reg;</span> Windows<span class="pl-c1">&amp;reg;</span> instance passwords.&lt;/<span class="pl-ent">p</span>&gt;</td>
      </tr>
      <tr>
        <td id="L732" class="blob-num js-line-number" data-line-number="732"></td>
        <td id="LC732" class="blob-code js-file-line">				<span class="pl-pse">&lt;?php</span><span class="pl-s2"> <span class="pl-k">endif</span>; </span><span class="pl-pse"><span class="pl-s2">?</span>&gt;</span></td>
      </tr>
      <tr>
        <td id="L733" class="blob-num js-line-number" data-line-number="733"></td>
        <td id="LC733" class="blob-code js-file-line">
</td>
      </tr>
      <tr>
        <td id="L734" class="blob-num js-line-number" data-line-number="734"></td>
        <td id="LC734" class="blob-code js-file-line">			<span class="pl-pse">&lt;?php</span><span class="pl-s2"> <span class="pl-k">else</span>: </span><span class="pl-pse"><span class="pl-s2">?</span>&gt;</span></td>
      </tr>
      <tr>
        <td id="L735" class="blob-num js-line-number" data-line-number="735"></td>
        <td id="LC735" class="blob-code js-file-line">			&lt;<span class="pl-ent">ol</span>&gt;</td>
      </tr>
      <tr>
        <td id="L736" class="blob-num js-line-number" data-line-number="736"></td>
        <td id="LC736" class="blob-code js-file-line">				<span class="pl-pse">&lt;?php</span><span class="pl-s2"> <span class="pl-k">if</span> (<span class="pl-k">!</span><span class="pl-vo">$php_ok</span>): </span><span class="pl-pse"><span class="pl-s2">?</span>&gt;</span></td>
      </tr>
      <tr>
        <td id="L737" class="blob-num js-line-number" data-line-number="737"></td>
        <td id="LC737" class="blob-code js-file-line">					&lt;<span class="pl-ent">li</span>&gt;&lt;<span class="pl-ent">strong</span>&gt;PHP:&lt;/<span class="pl-ent">strong</span>&gt; You are running an unsupported version of PHP.&lt;/<span class="pl-ent">li</span>&gt;</td>
      </tr>
      <tr>
        <td id="L738" class="blob-num js-line-number" data-line-number="738"></td>
        <td id="LC738" class="blob-code js-file-line">				<span class="pl-pse">&lt;?php</span><span class="pl-s2"> <span class="pl-k">endif</span>; </span><span class="pl-pse"><span class="pl-s2">?</span>&gt;</span></td>
      </tr>
      <tr>
        <td id="L739" class="blob-num js-line-number" data-line-number="739"></td>
        <td id="LC739" class="blob-code js-file-line">
</td>
      </tr>
      <tr>
        <td id="L740" class="blob-num js-line-number" data-line-number="740"></td>
        <td id="LC740" class="blob-code js-file-line">				<span class="pl-pse">&lt;?php</span><span class="pl-s2"> <span class="pl-k">if</span> (<span class="pl-k">!</span><span class="pl-vo">$curl_ok</span>): </span><span class="pl-pse"><span class="pl-s2">?</span>&gt;</span></td>
      </tr>
      <tr>
        <td id="L741" class="blob-num js-line-number" data-line-number="741"></td>
        <td id="LC741" class="blob-code js-file-line">					&lt;<span class="pl-ent">li</span>&gt;&lt;<span class="pl-ent">strong</span>&gt;cURL:&lt;/<span class="pl-ent">strong</span>&gt; The &lt;<span class="pl-ent">a</span> <span class="pl-e">href</span>=<span class="pl-s1"><span class="pl-pds">&quot;</span>http://php.net/curl<span class="pl-pds">&quot;</span></span>&gt;cURL&lt;/<span class="pl-ent">a</span>&gt; extension is not available. Without cURL, the SDK cannot connect to <span class="pl-c1">&amp;mdash;</span> or authenticate with <span class="pl-c1">&amp;mdash;</span> Amazon&#39;s services.&lt;/<span class="pl-ent">li</span>&gt;</td>
      </tr>
      <tr>
        <td id="L742" class="blob-num js-line-number" data-line-number="742"></td>
        <td id="LC742" class="blob-code js-file-line">				<span class="pl-pse">&lt;?php</span><span class="pl-s2"> <span class="pl-k">endif</span>; </span><span class="pl-pse"><span class="pl-s2">?</span>&gt;</span></td>
      </tr>
      <tr>
        <td id="L743" class="blob-num js-line-number" data-line-number="743"></td>
        <td id="LC743" class="blob-code js-file-line">
</td>
      </tr>
      <tr>
        <td id="L744" class="blob-num js-line-number" data-line-number="744"></td>
        <td id="LC744" class="blob-code js-file-line">				<span class="pl-pse">&lt;?php</span><span class="pl-s2"> <span class="pl-k">if</span> (<span class="pl-k">!</span><span class="pl-vo">$simplexml_ok</span>): </span><span class="pl-pse"><span class="pl-s2">?</span>&gt;</span></td>
      </tr>
      <tr>
        <td id="L745" class="blob-num js-line-number" data-line-number="745"></td>
        <td id="LC745" class="blob-code js-file-line">					&lt;<span class="pl-ent">li</span>&gt;&lt;<span class="pl-ent">strong</span>&gt;SimpleXML:&lt;/<span class="pl-ent">strong</span>&gt; The &lt;<span class="pl-ent">a</span> <span class="pl-e">href</span>=<span class="pl-s1"><span class="pl-pds">&quot;</span>http://php.net/simplexml<span class="pl-pds">&quot;</span></span>&gt;SimpleXML&lt;/<span class="pl-ent">a</span>&gt; extension is not available. Without SimpleXML, the SDK cannot parse the XML responses from Amazon&#39;s services.&lt;/<span class="pl-ent">li</span>&gt;</td>
      </tr>
      <tr>
        <td id="L746" class="blob-num js-line-number" data-line-number="746"></td>
        <td id="LC746" class="blob-code js-file-line">				<span class="pl-pse">&lt;?php</span><span class="pl-s2"> <span class="pl-k">endif</span>; </span><span class="pl-pse"><span class="pl-s2">?</span>&gt;</span></td>
      </tr>
      <tr>
        <td id="L747" class="blob-num js-line-number" data-line-number="747"></td>
        <td id="LC747" class="blob-code js-file-line">
</td>
      </tr>
      <tr>
        <td id="L748" class="blob-num js-line-number" data-line-number="748"></td>
        <td id="LC748" class="blob-code js-file-line">				<span class="pl-pse">&lt;?php</span><span class="pl-s2"> <span class="pl-k">if</span> (<span class="pl-k">!</span><span class="pl-vo">$dom_ok</span>): </span><span class="pl-pse"><span class="pl-s2">?</span>&gt;</span></td>
      </tr>
      <tr>
        <td id="L749" class="blob-num js-line-number" data-line-number="749"></td>
        <td id="LC749" class="blob-code js-file-line">					&lt;<span class="pl-ent">li</span>&gt;&lt;<span class="pl-ent">strong</span>&gt;DOM:&lt;/<span class="pl-ent">strong</span>&gt; The &lt;<span class="pl-ent">a</span> <span class="pl-e">href</span>=<span class="pl-s1"><span class="pl-pds">&quot;</span>http://php.net/dom<span class="pl-pds">&quot;</span></span>&gt;DOM&lt;/<span class="pl-ent">a</span>&gt; extension is not available. Without DOM, the SDK cannot transliterate JSON responses from Amazon&#39;s services into the common SimpleXML-based pattern used throughout the SDK.&lt;/<span class="pl-ent">li</span>&gt;</td>
      </tr>
      <tr>
        <td id="L750" class="blob-num js-line-number" data-line-number="750"></td>
        <td id="LC750" class="blob-code js-file-line">				<span class="pl-pse">&lt;?php</span><span class="pl-s2"> <span class="pl-k">endif</span>; </span><span class="pl-pse"><span class="pl-s2">?</span>&gt;</span></td>
      </tr>
      <tr>
        <td id="L751" class="blob-num js-line-number" data-line-number="751"></td>
        <td id="LC751" class="blob-code js-file-line">
</td>
      </tr>
      <tr>
        <td id="L752" class="blob-num js-line-number" data-line-number="752"></td>
        <td id="LC752" class="blob-code js-file-line">				<span class="pl-pse">&lt;?php</span><span class="pl-s2"> <span class="pl-k">if</span> (<span class="pl-k">!</span><span class="pl-vo">$spl_ok</span>): </span><span class="pl-pse"><span class="pl-s2">?</span>&gt;</span></td>
      </tr>
      <tr>
        <td id="L753" class="blob-num js-line-number" data-line-number="753"></td>
        <td id="LC753" class="blob-code js-file-line">					&lt;<span class="pl-ent">li</span>&gt;&lt;<span class="pl-ent">strong</span>&gt;SPL:&lt;/<span class="pl-ent">strong</span>&gt; &lt;<span class="pl-ent">a</span> <span class="pl-e">href</span>=<span class="pl-s1"><span class="pl-pds">&quot;</span>http://php.net/spl<span class="pl-pds">&quot;</span></span>&gt;Standard PHP Library&lt;/<span class="pl-ent">a</span>&gt; support is not available. Without SPL support, the SDK cannot autoload the required PHP classes.&lt;/<span class="pl-ent">li</span>&gt;</td>
      </tr>
      <tr>
        <td id="L754" class="blob-num js-line-number" data-line-number="754"></td>
        <td id="LC754" class="blob-code js-file-line">				<span class="pl-pse">&lt;?php</span><span class="pl-s2"> <span class="pl-k">endif</span>; </span><span class="pl-pse"><span class="pl-s2">?</span>&gt;</span></td>
      </tr>
      <tr>
        <td id="L755" class="blob-num js-line-number" data-line-number="755"></td>
        <td id="LC755" class="blob-code js-file-line">
</td>
      </tr>
      <tr>
        <td id="L756" class="blob-num js-line-number" data-line-number="756"></td>
        <td id="LC756" class="blob-code js-file-line">				<span class="pl-pse">&lt;?php</span><span class="pl-s2"> <span class="pl-k">if</span> (<span class="pl-k">!</span><span class="pl-vo">$json_ok</span>): </span><span class="pl-pse"><span class="pl-s2">?</span>&gt;</span></td>
      </tr>
      <tr>
        <td id="L757" class="blob-num js-line-number" data-line-number="757"></td>
        <td id="LC757" class="blob-code js-file-line">					&lt;<span class="pl-ent">li</span>&gt;&lt;<span class="pl-ent">strong</span>&gt;JSON:&lt;/<span class="pl-ent">strong</span>&gt; &lt;<span class="pl-ent">a</span> <span class="pl-e">href</span>=<span class="pl-s1"><span class="pl-pds">&quot;</span>http://php.net/json<span class="pl-pds">&quot;</span></span>&gt;JSON&lt;/<span class="pl-ent">a</span>&gt; support is not available. AWS leverages JSON heavily in many of its services.&lt;/<span class="pl-ent">li</span>&gt;</td>
      </tr>
      <tr>
        <td id="L758" class="blob-num js-line-number" data-line-number="758"></td>
        <td id="LC758" class="blob-code js-file-line">				<span class="pl-pse">&lt;?php</span><span class="pl-s2"> <span class="pl-k">endif</span>; </span><span class="pl-pse"><span class="pl-s2">?</span>&gt;</span></td>
      </tr>
      <tr>
        <td id="L759" class="blob-num js-line-number" data-line-number="759"></td>
        <td id="LC759" class="blob-code js-file-line">
</td>
      </tr>
      <tr>
        <td id="L760" class="blob-num js-line-number" data-line-number="760"></td>
        <td id="LC760" class="blob-code js-file-line">				<span class="pl-pse">&lt;?php</span><span class="pl-s2"> <span class="pl-k">if</span> (<span class="pl-k">!</span><span class="pl-vo">$pcre_ok</span>): </span><span class="pl-pse"><span class="pl-s2">?</span>&gt;</span></td>
      </tr>
      <tr>
        <td id="L761" class="blob-num js-line-number" data-line-number="761"></td>
        <td id="LC761" class="blob-code js-file-line">					&lt;<span class="pl-ent">li</span>&gt;&lt;<span class="pl-ent">strong</span>&gt;PCRE:&lt;/<span class="pl-ent">strong</span>&gt; Your PHP installation doesn&#39;t support Perl-Compatible Regular Expressions (PCRE). Without PCRE, the SDK cannot do any filtering via regular expressions.&lt;/<span class="pl-ent">li</span>&gt;</td>
      </tr>
      <tr>
        <td id="L762" class="blob-num js-line-number" data-line-number="762"></td>
        <td id="LC762" class="blob-code js-file-line">				<span class="pl-pse">&lt;?php</span><span class="pl-s2"> <span class="pl-k">endif</span>; </span><span class="pl-pse"><span class="pl-s2">?</span>&gt;</span></td>
      </tr>
      <tr>
        <td id="L763" class="blob-num js-line-number" data-line-number="763"></td>
        <td id="LC763" class="blob-code js-file-line">
</td>
      </tr>
      <tr>
        <td id="L764" class="blob-num js-line-number" data-line-number="764"></td>
        <td id="LC764" class="blob-code js-file-line">				<span class="pl-pse">&lt;?php</span><span class="pl-s2"> <span class="pl-k">if</span> (<span class="pl-k">!</span><span class="pl-vo">$file_ok</span>): </span><span class="pl-pse"><span class="pl-s2">?</span>&gt;</span></td>
      </tr>
      <tr>
        <td id="L765" class="blob-num js-line-number" data-line-number="765"></td>
        <td id="LC765" class="blob-code js-file-line">					&lt;<span class="pl-ent">li</span>&gt;&lt;<span class="pl-ent">strong</span>&gt;File System Read/Write:&lt;/<span class="pl-ent">strong</span>&gt; The &lt;<span class="pl-ent">a</span> <span class="pl-e">href</span>=<span class="pl-s1"><span class="pl-pds">&quot;</span>http://php.net/file_get_contents<span class="pl-pds">&quot;</span></span>&gt;file_get_contents()&lt;/<span class="pl-ent">a</span>&gt; and/or &lt;<span class="pl-ent">a</span> <span class="pl-e">href</span>=<span class="pl-s1"><span class="pl-pds">&quot;</span>http://php.net/file_put_contents<span class="pl-pds">&quot;</span></span>&gt;file_put_contents()&lt;/<span class="pl-ent">a</span>&gt; functions have been disabled. Without them, the SDK cannot read from, or write to, the file system.&lt;/<span class="pl-ent">li</span>&gt;</td>
      </tr>
      <tr>
        <td id="L766" class="blob-num js-line-number" data-line-number="766"></td>
        <td id="LC766" class="blob-code js-file-line">				<span class="pl-pse">&lt;?php</span><span class="pl-s2"> <span class="pl-k">endif</span>; </span><span class="pl-pse"><span class="pl-s2">?</span>&gt;</span></td>
      </tr>
      <tr>
        <td id="L767" class="blob-num js-line-number" data-line-number="767"></td>
        <td id="LC767" class="blob-code js-file-line">			&lt;/<span class="pl-ent">ol</span>&gt;</td>
      </tr>
      <tr>
        <td id="L768" class="blob-num js-line-number" data-line-number="768"></td>
        <td id="LC768" class="blob-code js-file-line">			<span class="pl-pse">&lt;?php</span><span class="pl-s2"> <span class="pl-k">endif</span>; </span><span class="pl-pse"><span class="pl-s2">?</span>&gt;</span></td>
      </tr>
      <tr>
        <td id="L769" class="blob-num js-line-number" data-line-number="769"></td>
        <td id="LC769" class="blob-code js-file-line">		&lt;/<span class="pl-ent">div</span>&gt;</td>
      </tr>
      <tr>
        <td id="L770" class="blob-num js-line-number" data-line-number="770"></td>
        <td id="LC770" class="blob-code js-file-line">
</td>
      </tr>
      <tr>
        <td id="L771" class="blob-num js-line-number" data-line-number="771"></td>
        <td id="LC771" class="blob-code js-file-line">		&lt;<span class="pl-ent">div</span> <span class="pl-e">class</span>=<span class="pl-s1"><span class="pl-pds">&quot;</span>chunk<span class="pl-pds">&quot;</span></span>&gt;</td>
      </tr>
      <tr>
        <td id="L772" class="blob-num js-line-number" data-line-number="772"></td>
        <td id="LC772" class="blob-code js-file-line">			&lt;<span class="pl-ent">p</span> <span class="pl-e">class</span>=<span class="pl-s1"><span class="pl-pds">&quot;</span>footnote<span class="pl-pds">&quot;</span></span>&gt;&lt;<span class="pl-ent">strong</span>&gt;NOTE&lt;/<span class="pl-ent">strong</span>&gt;: Passing this test does not guarantee that the AWS SDK for PHP will run on your web server <span class="pl-c1">&amp;mdash;</span> it only ensures that the requirements have been addressed.&lt;/<span class="pl-ent">p</span>&gt;</td>
      </tr>
      <tr>
        <td id="L773" class="blob-num js-line-number" data-line-number="773"></td>
        <td id="LC773" class="blob-code js-file-line">		&lt;/<span class="pl-ent">div</span>&gt;</td>
      </tr>
      <tr>
        <td id="L774" class="blob-num js-line-number" data-line-number="774"></td>
        <td id="LC774" class="blob-code js-file-line">	&lt;/<span class="pl-ent">div</span>&gt;</td>
      </tr>
      <tr>
        <td id="L775" class="blob-num js-line-number" data-line-number="775"></td>
        <td id="LC775" class="blob-code js-file-line">
</td>
      </tr>
      <tr>
        <td id="L776" class="blob-num js-line-number" data-line-number="776"></td>
        <td id="LC776" class="blob-code js-file-line">&lt;/<span class="pl-ent">div</span>&gt;</td>
      </tr>
      <tr>
        <td id="L777" class="blob-num js-line-number" data-line-number="777"></td>
        <td id="LC777" class="blob-code js-file-line">
</td>
      </tr>
      <tr>
        <td id="L778" class="blob-num js-line-number" data-line-number="778"></td>
        <td id="LC778" class="blob-code js-file-line"><span class="pl-pse">&lt;?php</span><span class="pl-s2"> <span class="pl-k">if</span> (<span class="pl-k">!</span>is_windows()): </span><span class="pl-pse"><span class="pl-s2">?</span>&gt;</span></td>
      </tr>
      <tr>
        <td id="L779" class="blob-num js-line-number" data-line-number="779"></td>
        <td id="LC779" class="blob-code js-file-line"><span class="pl-s2">&lt;<span class="pl-ent">script</span> <span class="pl-e">type</span>=<span class="pl-s1"><span class="pl-pds">&quot;</span>text/javascript<span class="pl-pds">&quot;</span></span> <span class="pl-e">charset</span>=<span class="pl-s1"><span class="pl-pds">&quot;</span>utf-8<span class="pl-pds">&quot;</span></span>&gt;</span></td>
      </tr>
      <tr>
        <td id="L780" class="blob-num js-line-number" data-line-number="780"></td>
        <td id="LC780" class="blob-code js-file-line"><span class="pl-s2">reqwest(<span class="pl-s1"><span class="pl-pds">&#39;</span><span class="pl-pse">&lt;?php</span><span class="pl-s2"> <span class="pl-s3">echo</span> <span class="pl-s3">pathinfo</span>(<span class="pl-c1">__FILE__</span>, <span class="pl-sc">PATHINFO_BASENAME</span>); </span><span class="pl-pse"><span class="pl-s2">?</span>&gt;</span>?ssl_check<span class="pl-pds">&#39;</span></span>, <span class="pl-st">function</span>(<span class="pl-vpf">resp</span>) {</span></td>
      </tr>
      <tr>
        <td id="L781" class="blob-num js-line-number" data-line-number="781"></td>
        <td id="LC781" class="blob-code js-file-line"><span class="pl-s2">	$sslCheck <span class="pl-k">=</span> <span class="pl-s3">document</span>.<span class="pl-s3">getElementById</span>(<span class="pl-s1"><span class="pl-pds">&#39;</span>ssl_check<span class="pl-pds">&#39;</span></span>);</span></td>
      </tr>
      <tr>
        <td id="L782" class="blob-num js-line-number" data-line-number="782"></td>
        <td id="LC782" class="blob-code js-file-line"><span class="pl-s2">	$sslCheck.innerHTML <span class="pl-k">=</span> <span class="pl-s1"><span class="pl-pds">&#39;</span><span class="pl-pds">&#39;</span></span>;</span></td>
      </tr>
      <tr>
        <td id="L783" class="blob-num js-line-number" data-line-number="783"></td>
        <td id="LC783" class="blob-code js-file-line"><span class="pl-s2">	$sslCheck.innerHTML <span class="pl-k">=</span> <span class="pl-s1"><span class="pl-pds">&#39;</span>&lt;code&gt;<span class="pl-pds">&#39;</span></span> <span class="pl-k">+</span> resp <span class="pl-k">+</span> <span class="pl-s1"><span class="pl-pds">&#39;</span>&lt;/code&gt;<span class="pl-pds">&#39;</span></span>;</span></td>
      </tr>
      <tr>
        <td id="L784" class="blob-num js-line-number" data-line-number="784"></td>
        <td id="LC784" class="blob-code js-file-line"><span class="pl-s2">});</span></td>
      </tr>
      <tr>
        <td id="L785" class="blob-num js-line-number" data-line-number="785"></td>
        <td id="LC785" class="blob-code js-file-line"><span class="pl-s2">&lt;/<span class="pl-ent">script</span>&gt;</span></td>
      </tr>
      <tr>
        <td id="L786" class="blob-num js-line-number" data-line-number="786"></td>
        <td id="LC786" class="blob-code js-file-line"><span class="pl-pse">&lt;?php</span><span class="pl-s2"> <span class="pl-k">endif</span>; </span><span class="pl-pse"><span class="pl-s2">?</span>&gt;</span></td>
      </tr>
      <tr>
        <td id="L787" class="blob-num js-line-number" data-line-number="787"></td>
        <td id="LC787" class="blob-code js-file-line">
</td>
      </tr>
      <tr>
        <td id="L788" class="blob-num js-line-number" data-line-number="788"></td>
        <td id="LC788" class="blob-code js-file-line">&lt;/<span class="pl-ent">body</span>&gt;</td>
      </tr>
      <tr>
        <td id="L789" class="blob-num js-line-number" data-line-number="789"></td>
        <td id="LC789" class="blob-code js-file-line">&lt;/<span class="pl-ent">html</span>&gt;</td>
      </tr>
</table>

  </div>

  </div>
</div>

<a href="#jump-to-line" rel="facebox[.linejump]" data-hotkey="l" style="display:none">Jump to Line</a>
<div id="jump-to-line" style="display:none">
  <form accept-charset="UTF-8" class="js-jump-to-line-form">
    <input class="linejump-input js-jump-to-line-field" type="text" placeholder="Jump to line&hellip;" autofocus>
    <button type="submit" class="button">Go</button>
  </form>
</div>

        </div>

      </div><!-- /.repo-container -->
      <div class="modal-backdrop"></div>
    </div><!-- /.container -->
  </div><!-- /.site -->


    </div><!-- /.wrapper -->

      <div class="container">
  <div class="site-footer" role="contentinfo">
    <ul class="site-footer-links right">
      <li><a href="https://status.github.com/">Status</a></li>
      <li><a href="https://developer.github.com">API</a></li>
      <li><a href="http://training.github.com">Training</a></li>
      <li><a href="http://shop.github.com">Shop</a></li>
      <li><a href="/blog">Blog</a></li>
      <li><a href="/about">About</a></li>

    </ul>

    <a href="/" aria-label="Homepage">
      <span class="mega-octicon octicon-mark-github" title="GitHub"></span>
    </a>

    <ul class="site-footer-links">
      <li>&copy; 2014 <span title="0.03937s from github-fe118-cp1-prd.iad.github.net">GitHub</span>, Inc.</li>
        <li><a href="/site/terms">Terms</a></li>
        <li><a href="/site/privacy">Privacy</a></li>
        <li><a href="/security">Security</a></li>
        <li><a href="/contact">Contact</a></li>
    </ul>
  </div><!-- /.site-footer -->
</div><!-- /.container -->


    <div class="fullscreen-overlay js-fullscreen-overlay" id="fullscreen_overlay">
  <div class="fullscreen-container js-suggester-container">
    <div class="textarea-wrap">
      <textarea name="fullscreen-contents" id="fullscreen-contents" class="fullscreen-contents js-fullscreen-contents js-suggester-field" placeholder=""></textarea>
    </div>
  </div>
  <div class="fullscreen-sidebar">
    <a href="#" class="exit-fullscreen js-exit-fullscreen tooltipped tooltipped-w" aria-label="Exit Zen Mode">
      <span class="mega-octicon octicon-screen-normal"></span>
    </a>
    <a href="#" class="theme-switcher js-theme-switcher tooltipped tooltipped-w"
      aria-label="Switch themes">
      <span class="octicon octicon-color-mode"></span>
    </a>
  </div>
</div>



    <div id="ajax-error-message" class="flash flash-error">
      <span class="octicon octicon-alert"></span>
      <a href="#" class="octicon octicon-x flash-close js-ajax-error-dismiss" aria-label="Dismiss error"></a>
      Something went wrong with that request. Please try again.
    </div>


      <script crossorigin="anonymous" src="https://assets-cdn.github.com/assets/frameworks-1dca3eab4ab3b2a00235feebb2fc218f0e91bbe06e140fb6ca67049215c66508.js" type="text/javascript"></script>
      <script async="async" crossorigin="anonymous" src="https://assets-cdn.github.com/assets/github-8030280dc7cfdfbef21fdfd9af393dfb804303a800485e6ae16d6521852e0d78.js" type="text/javascript"></script>
      
      
  </body>
</html>

