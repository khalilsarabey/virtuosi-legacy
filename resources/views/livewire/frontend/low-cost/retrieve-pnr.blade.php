<div class="container">
    <div class="py-4">
        <form method="post" action="{{ route('frontend.low-cost.post-retrievepnr') }}">
            @csrf
            <div>
                <label>Booking Reference</label>
                <input type="text" name="bookref" class="form-control password_expiry" required>
            </div>
            <div>
                <label>Airline</label>
                <select name="airline" class="form-control form-select w-100" required>
                    <option value="">Choose Airline</option>
                    <option value="FlyDubai">FlyDubai</option>
                    <option value="AirArabia">AirArabia</option>
                </select>
            </div>
            <button type="submit" class="text-uppercase text-white w-100 border-0 h6 bg-primary p-3">Retrieve</button>
        </form>
    </div>
</div>
