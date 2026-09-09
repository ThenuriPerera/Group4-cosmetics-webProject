// Shared presentation only; module cart and quiz handlers stay in their files.
document.documentElement.classList.add('js');
document.addEventListener('DOMContentLoaded', () => {
 const toggle=document.querySelector('.menu-toggle'), nav=document.querySelector('#main-nav');
 if(toggle && nav) {
  toggle.addEventListener('click',()=>{const open=toggle.getAttribute('aria-expanded')!=='true';toggle.setAttribute('aria-expanded',String(open));nav.classList.toggle('is-open',open);});
  document.addEventListener('keydown',e=>{if(e.key==='Escape' && nav.classList.contains('is-open')){nav.classList.remove('is-open');toggle.setAttribute('aria-expanded','false');toggle.focus();}});
 }
 const here=location.pathname.endsWith('/')?location.pathname+'index.php':location.pathname;
 document.querySelectorAll('#main-nav a,.section-nav a,.admin-sidebar a').forEach(a=>{if(new URL(a.href).pathname===here){a.classList.add('active');a.setAttribute('aria-current','page');}});
 document.querySelectorAll('[data-confirm]').forEach(a=>a.addEventListener('click',e=>{if(!window.confirm(a.dataset.confirm))e.preventDefault();}));
 document.querySelectorAll('table').forEach(table=>{const wrap=document.createElement('div');wrap.className='table-scroll';wrap.tabIndex=0;wrap.setAttribute('role','region');wrap.setAttribute('aria-label','Scrollable table');table.before(wrap);wrap.append(table);});
 document.querySelectorAll('img').forEach(img=>{const missing=()=>{if(!img.isConnected)return;const box=document.createElement('div');box.className='product-placeholder';box.textContent='Photo coming soon';img.replaceWith(box);};img.addEventListener('error',missing,{once:true});if(img.complete && !img.naturalWidth)missing();});
});
