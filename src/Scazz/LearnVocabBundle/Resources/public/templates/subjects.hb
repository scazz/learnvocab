<div class="col-md-12">
  <div class="list-group">
    <div class="list-group-item">
      <strong> Select your subject </strong>
    </div>

    {{#each custom}}
	    <div class="list-group-item">
	      {{#link-to "topics" this}}{{name}}{{/link-to}}
	      <button type="button" class="padded-button btn btn-danger pull-right trash" {{action "deleteSubjectWithConfirmation" this}}><i class="fa fa-trash-o"></i></button>
	    </div>
 	{{/each}}
  </div>
</div>

<div class="col-md-12">
  <div class="list-group">
    <div class="list-group-item">
      <strong>Choose from our pre-populated lists</strong>
    </div>

     {{#each templates}}
     	<div class="list-group-item">
	      <a {{action "cloneTemplateSubject" this}}>{{name}}</a>
	      <button type="button" class="padded-button btn btn-success pull-right trash" {{action "cloneTemplateSubject" this}}><i class="fa fa-play"></i></button>
	    </div>
     {{/each}}
 </div>
</div>

<div class="col-md-12">
  <div class="list-group">
    <div class="list-group-item">
      <strong> Create a new subject </strong>
    </div>
    <div class="list-group-item">
      {{input action="createNewSubject" class="subject" value=newSubjectName placeholder="Enter new subject name" style="width:100%" }}
    </div>
  </div>
</div> 


{{ outlet 'confirmation'}}
{{outlet}}