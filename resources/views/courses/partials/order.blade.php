<div class="container text-right">
    <div class="form-group border-bottom pb-2">
        <label for="order" class="mr-3">Rendezés: </label>
        <select name="order" id="orderSelector" form="filterForm">
            <option value="orderByLengthDesc" {{request()->input('order', null) == 'orderByLengthDesc' ? 'selected=true' : ''}}>Longest first</option>
            <option value="orderByLengthAsc" {{request()->input('order', null) == 'orderByLengthAsc' ? 'selected=true' : ''}}>Shortest first</option>
            <option value="orderBySubscribersDesc" {{request()->input('order', null) == 'orderBySubscribersDesc' ? 'selected=true' : ''}}>Most subscribers</option>
            <option value="orderBySubscribersAsc" {{request()->input('order', null) == 'orderBySubscribersAsc' ? 'selected=true' : ''}}>Least subscribers</option>
        </select>
    </div>
</div>
