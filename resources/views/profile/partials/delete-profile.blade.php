<button type="button" class="btn btn-danger" data-toggle="modal" data-target="#confirmDeleteModal">
    Delete account
</button>

<!-- Modal -->
<div class="modal fade" id="confirmDeleteModal" tabindex="-1" role="dialog" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmDeleteModalLabel">Are you sure?</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>If you delete your account, it <b>can never be</b> restored!</p>
                <p>All your data is deleted, if you register again later, your saved courses, pending subscriptions are not restoreable.</p>
                <p>Are you absolutely sure to continue?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <form method="post" action="{{route('profile.delete')}}">
                    @csrf
                    <input type="hidden" name="userId" value="{{Auth::user()->id}}">
                    <button type="submit" class="btn btn-danger">Got it, delete my account.</button>
                </form>
            </div>
        </div>
    </div>
</div>
