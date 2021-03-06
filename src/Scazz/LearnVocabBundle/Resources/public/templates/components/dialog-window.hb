<!-- Modal -->
<div class="modal" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="false">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="myModalLabel">Confirm delete</h4>
      </div>
      <div class="modal-body">
      	Are you sure you want to delete?
      </div>
   <div class="modal-footer">
        <button type="button" class="btn btn-default" {{action "cancel"}}>Cancel</button>
        <button type="button" class="btn btn-primary" {{action "confirm"}}>Confirm</button>
      </div> 
    </div>
  </div>
</div>