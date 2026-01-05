       <link rel="stylesheet" href="/assets/css/subscription.css">

       <form id="planForm" method="POST" action="/admin/plan/save">
           <input type="hidden" name="plan_id" id="plan_id">
           
           <div class="form-group">
               <label for="plan_name">Plan Name</label>
               <input type="text" name="plan_name" id="plan_name" placeholder="Enter plan name" required>
           </div>
           
           <div class="form-group">
               <label for="price">Price (₹)</label>
               <input type="number" name="price" id="price" placeholder="Enter price" required>
           </div>
           
           <div class="form-group">
               <label for="billing_cycle">Billing Cycle</label>
               <select name="billing_cycle" id="billing_cycle">
                   <option value="monthly">Monthly</option>
                   <option value="yearly">Yearly</option>
               </select>
           </div>
           
           <div class="form-group">
               <label for="description">Description</label>
               <textarea name="description" id="description" placeholder="Enter plan description"></textarea>
           </div>
           
           <button type="submit">Save Plan</button>
       </form>