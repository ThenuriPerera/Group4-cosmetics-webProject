// Uses the existing cart AJAX actions; surfaces failures instead of silently ignoring them.
const cartTable = document.querySelector('#cart-table');
if (cartTable) {
 const feedback = document.querySelector('#cart-feedback');
 const controls = () => [...cartTable.querySelectorAll('.qty-input,.remove-btn')];
 cartTable.querySelectorAll('.qty-input').forEach(input => input.dataset.saved = input.value);
 async function updateCart(action, control) {
  const row = control.closest('tr');
  const quantity = Math.max(1, parseInt(control.value || '1', 10) || 1);
  controls().forEach(c => c.disabled = true);
  feedback.hidden = false; feedback.className = ''; feedback.textContent = 'Updating your bag…';
  try {
   const response = await fetch(cartTable.dataset.endpoint, {
    method:'POST', headers:{'Content-Type':'application/x-www-form-urlencoded'},
    body:new URLSearchParams({action,cart_item_id:control.dataset.cartItemId,quantity:String(quantity)})
   });
   const data = await response.json();
   if (!response.ok || !data.success) throw new Error(data.error || 'Your bag could not be updated. Please try again.');
   if (action === 'remove') row.remove();
   else {
    control.value = String(quantity); control.dataset.saved = control.value;
    row.querySelector('.row-subtotal').textContent = 'Rs. ' + (Number(row.dataset.price) * quantity).toLocaleString('en-US',{minimumFractionDigits:2,maximumFractionDigits:2});
   }
   document.querySelector('#cart-total-amount').textContent = data.cart_total;
   const empty = !cartTable.querySelector('tbody tr[data-cart-item-id]');
   document.querySelector('#checkout-link').hidden = empty;
   if (empty) {
    const tr = document.createElement('tr'), td = document.createElement('td');
    td.colSpan = 5; td.textContent = 'Your bag is empty. Explore the collection to find your next favourite.';
    tr.append(td); cartTable.querySelector('tbody').replaceChildren(tr);
   }
   feedback.className = 'success'; feedback.textContent = action === 'remove' ? 'Item removed from your bag.' : 'Quantity updated.';
  } catch(error) {
   if (action === 'update_quantity') control.value = control.dataset.saved;
   feedback.className = 'error';
   feedback.textContent = error instanceof SyntaxError ? 'The server could not update your bag. Refresh the page and try again.' : error.message;
  } finally { controls().forEach(c => c.disabled = false); }
 }
 cartTable.querySelectorAll('.qty-input').forEach(input => input.addEventListener('change',()=>updateCart('update_quantity',input)));
 cartTable.querySelectorAll('.remove-btn').forEach(button => button.addEventListener('click',()=>updateCart('remove',button)));
}
