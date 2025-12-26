       <link rel="stylesheet" href="/assets/css/subscription.css">

       <form id="planForm" method="POST" action="/admin/plan/save">
            <input type="hidden" name="plan_id" id="plan_id">
            <label>Plan Name</label><br>
            <input type="text" name="plan_name" id="plan_name" required><br>
            <label>Price</label><br>
            <input type="number" name="price" id="price" required><br>
            <label>Billing Cycle</label><br>
            <select name="billing_cycle" id="billing_cycle">
                <option value="monthly">Monthly</option>
                <option value="yearly">Yearly</option>
            </select><br>
            <label>Description</label><br>
            <textarea name="description" id="description"></textarea><br><br>
            <button type="submit">Save</button>
        </form>