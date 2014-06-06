<div class="navbar navbar-default" role="navigation">
	<div class="container-fluid">
		
		<div class="navbar-header">
    	<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
      	<span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="#">Learn Vocab</a>
    </div>
    

    <div class="navbar-collapse collapse">
      <ul class="nav navbar-nav">
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown">Choose subject<b class="caret"></b></a>
          <ul class="dropdown-menu">
    	     	{{#each availableSubjects}}
              <li> {{#link-to 'topics' this}}{{name}}{{/link-to}}</a></li>
            {{/each}}
           	<li class="divider"></li>
            <li>{{#link-to 'subjects'}}Manage subjects{{/link-to}}</li>
      	  </ul>
        </li>
      </ul>

      {{#if availableTopics}}
	      <ul class="nav navbar-nav">
	        <li class="dropdown">
	          <a href="#" class="dropdown-toggle" data-toggle="dropdown">Choose topic<b class="caret"></b></a>
	          
	          <ul class="dropdown-menu">
              {{#each availableTopics}}
                <li>{{#link-to 'vocabs' this}}{{name}}{{/link-to}}</li>
              {{/each}}
              <li class="divider"></li>
              <li>{{#link-to 'topics'}}Manage topics{{/link-to}}</a></li>
            </ul>
          </li>
        </ul>
      {{/if}}
      
      {{#if availableVocabs}}
      <ul class="nav navbar-nav navbar-right">
        <li >{{#link-to 'topic.test'}}Test yourself{{/link-to}}</li>
      </ul>
      {{/if}}
    </div><!--/.nav-collapse -->
  </div><!--/.container-fluid -->
</div>
