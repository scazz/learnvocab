<div class="vocabtable">
	<div class="vocabrow new-vocab-row">
	  <div class="vocab native">
	  	{{new-vocab-word valid=isNativeInputValid placeholder="New native word or phrase" value=newNative action="createNewVocab"}}
	  </div>
	  <div class="vocab translation">
	    {{new-vocab-word valid=isTranslatedInputValid placeholder="Translated word or phrase" value=newTranslated action="createNewVocab"}}
	    <button class="btn btn-small btn-success save-button" {{action "createNewVocab"}}><i class="fa fa-check"></i></button>
	  </div>
	</div><Br />

	{{#each itemController="vocab"}}
		<div  {{bind-attr class=":vocabrow isLearnt:completed"}}>
  			<div class="vocab native"> {{input type="checkbox" checked=isLearnt class="togglelearned" }} {{native}} </div>
  			<div class="vocab translation">
  				{{translated}} 
  				<button class="btn dtn-small btn-danger pull-right" {{action "deleteVocabWithConfirmation" this}}>
  					<i class="fa fa-trash-o"></i>
  				</button>
  			</div>
		</div>
	{{/each}}
</div>

{{outlet}}





