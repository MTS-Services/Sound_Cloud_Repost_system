 <x-frontend::layout>

    <x-slot name="title">Select Payment Method</x-slot>
    <x-slot name="page_slug">payment-method</x-slot>

<div class="min-h-screen bg-gradient-to-br from-orange-50 via-white to-orange-100 relative overflow-hidden font-[Inter]"> 

 <div class="absolute top-20 left-10 w-32 h-32 bg-orange-200 dark:bg-black rounded-full opacity-20 animate-bounce"></div> 
 <div class="absolute top-40 right-20 w-24 h-24 bg-orange-300 rounded-full opacity-30 animate-bounce delay-200"></div> 
 <div class="absolute bottom-20 left-1/4 w-20 h-20 bg-orange-400 rounded-full opacity-25 animate-bounce delay-500"></div> 

 <div class="container mx-auto px-4 py-12 relative z-10"> 
    <div class="text-center mb-12"> 
   <div class="relative inline-block mb-6"> 
    <div class="absolute inset-0 bg-orange-600 rounded-full opacity-20 animate-ping"></div> 
    <div class="relative bg-orange-600 text-white p-4 rounded-full inline-flex items-center justify-center"> 
     <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"> 
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
       d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/> 
     </svg> 
    </div> 
   </div> 
   <h1 class="text-4xl md:text-5xl font-bold text-gray-900 mb-4">Choose Your Payment Method</h1> 
   <p class="text-lg text-gray-600 max-w-2xl mx-auto leading-relaxed"> 
    Select your preferred payment gateway to complete your transaction securely and efficiently. 
   </p> 
  </div> 

    <form id="paymentForm" class="max-w-4xl mx-auto space-y-6"> 
      <div onclick="selectPayment('paypal')" 
     class="relative transition-all duration-300 ease-in-out p-8 rounded-2xl bg-white/90 border border-white/30 backdrop-blur cursor-pointer hover:-translate-y-2 hover:shadow-2xl payment-card" 
     id="paypal-card"> 
    <div class="flex items-center justify-between"> 
     <div class="flex items-center space-x-6"> 
      <input type="radio" id="paypal" name="payment" value="paypal" class="w-5 h-5 border-2 border-gray-300 rounded-full checked:bg-orange-500 checked:border-orange-500"> 
      <div class="flex items-center space-x-4"> 
       <div class="bg-blue-600 p-4 rounded-xl transform transition-transform group-hover:scale-110"> 
        <svg class="w-8 h-8 text-white" viewBox="0 0 24 24" fill="currentColor"> 
         <path d="M7.076 21.337H2.47a.641.641 0 0 1-.633-.74L4.944.901C5.026.382 5.474 0 5.998 0h7.46c2.57 0 4.578.543 5.69 1.81 1.01 1.15 1.304 2.42 1.012 4.287-.023.143-.047.288-.077.437-.983 4.814-4.622 6.461-8.956 6.461H8.563c-.766 0-1.41.541-1.549 1.297l-.542 3.24-.077.49c-.064.399-.41.72-.817.72z"/> 
        </svg> 
       </div> 
       <div> 
        <h3 class="text-2xl font-semibold text-gray-900 mb-1">PayPal</h3> 
        <p class="text-gray-600">Pay securely with your PayPal account or credit card.</p> 
       </div> 
      </div> 
     </div> 
     <div class="hidden md:flex items-center space-x-2"> 
      <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm">Secure</span> 
      <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm">Global</span> 
     </div> 
    </div> 
    <div class="flex flex-wrap gap-3 mt-6 text-sm text-gray-700"> 
     <div class="bg-gray-100 px-4 py-2 rounded-lg flex items-center space-x-2"> 
      ✅ <span>Buyer Protection</span> 
     </div> 
     <div class="bg-gray-100 px-4 py-2 rounded-lg flex items-center space-x-2"> 
      ✅ <span>No account required</span> 
     </div> 
     <div class="bg-gray-100 px-4 py-2 rounded-lg flex items-center space-x-2"> 
      ✅ <span>24/7 Support</span> 
     </div> 
    </div> 
   </div> 

      <div onclick="selectPayment('stripe')" 
     class="relative transition-all duration-300 ease-in-out p-8 rounded-2xl bg-white/90 border border-white/30 backdrop-blur cursor-pointer hover:-translate-y-2 hover:shadow-2xl payment-card" 
     id="stripe-card"> 
    <div class="flex items-center justify-between"> 
     <div class="flex items-center space-x-6"> 
      <input type="radio" id="stripe" name="payment" value="stripe" class="w-5 h-5 border-2 border-gray-300 rounded-full checked:bg-orange-500 checked:border-orange-500"> 
      <div class="flex items-center space-x-4"> 
       <div class="bg-indigo-600 p-4 rounded-xl transform transition-transform group-hover:scale-110"> 
        <svg class="w-8 h-8 text-white" viewBox="0 0 24 24" fill="currentColor"> 
         <path d="M13.976 9.15c-2.172-.806-3.356-1.426-3.356-2.409 0-.831.683-1.305 1.901-1.305 2.227 0 4.515.858 6.09 1.631l.89-5.494C18.252.975 15.697 0 12.165 0 9.667 0 7.589.654 6.104 1.872 4.56 3.147 3.757 4.992 3.757 7.218c0 4.039 2.467 5.76 6.476 7.219 2.585.92 3.445 1.574 3.445 2.583 0 .98-.84 1.545-2.354 1.545-1.875 0-4.965-.921-6.99-2.109l-.9 5.555C5.175 22.99 8.385 24 11.714 24c2.641 0 4.843-.624 6.328-1.813 1.664-1.305 2.525-3.236 2.525-5.732 0-4.128-2.524-5.851-6.594-7.305z"/> 
        </svg> 
       </div> 
       <div> 
        <h3 class="text-2xl font-semibold text-gray-900 mb-1">Stripe</h3> 
        <p class="text-gray-600">Supports all major credit cards & digital wallets.</p> 
       </div> 
      </div> 
     </div> 
     <div class="hidden md:flex items-center space-x-2"> 
      <span class="bg-purple-100 text-purple-800 px-3 py-1 rounded-full text-sm">Advanced</span> 
      <span class="bg-indigo-100 text-indigo-800 px-3 py-1 rounded-full text-sm">Fast</span> 
     </div> 
    </div> 
    <div class="flex flex-wrap gap-3 mt-6 text-sm text-gray-700"> 
     <div class="bg-gray-100 px-4 py-2 rounded-lg flex items-center space-x-2"> 
      ✅ <span>SSL Encrypted</span> 
     </div> 
     <div class="bg-gray-100 px-4 py-2 rounded-lg flex items-center space-x-2"> 
      ✅ <span>Apple Pay & Google Pay</span> 
     </div> 
     <div class="bg-gray-100 px-4 py-2 rounded-lg flex items-center space-x-2"> 
      ✅ <span>PCI Compliant</span> 
     </div> 
    </div> 
   </div> 

      <div class="text-center pt-8"> 
    <button type="submit" id="continueBtn" 
        class="bg-gradient-to-r from-orange-500 to-orange-600 text-white px-12 py-4 rounded-xl font-semibold text-lg shadow-md hover:scale-105 transition-all disabled:opacity-50 disabled:cursor-not-allowed" 
        disabled> 
     <span class="flex items-center justify-center gap-2"> 
      <span>Continue to Payment</span> 
      <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"> 
       <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/> 
      </svg> 
     </span> 
    </button> 
   </div> 
  </form> 

    <div class="mt-16 text-center text-sm text-gray-500"> 
   <p>Your payment information is encrypted and secure. We never store your payment details.</p> 
  </div> 
 </div> 

 <script> 
  function selectPayment(type) { 
   document.querySelectorAll('.payment-card').forEach(card => card.classList.remove('ring-2', 'ring-orange-500')); 
   document.getElementById(`${type}-card`).classList.add('ring-2', 'ring-orange-500'); 
   document.getElementById(type).checked = true; 
   document.getElementById('continueBtn').disabled = false; 
  } 

  document.getElementById('paymentForm').addEventListener('submit', function(e) { 
   e.preventDefault(); 
   const selected = document.querySelector('input[name="payment"]:checked'); 
   if (selected) { 
    const btn = document.getElementById('continueBtn'); 
    btn.innerHTML = ` 
     <span class="flex items-center justify-center gap-2"> 
      <svg class="animate-spin w-5 h-5" fill="none" viewBox="0 0 24 24"> 
       <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle> 
       <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path> 
      </svg> 
      <span>Processing...</span> 
     </span>`; 
    btn.disabled = true; 

 setTimeout(() => { 
 // Replace the alert with actual redirection
 if (selected.value === 'paypal') {
 window.location.href = "{{ route('paypal.paymentLink', encrypt($order->id)) }}"; 
 } else if (selected.value === 'stripe') {
 window.location.href = "{{ route('f.payment.form', encrypt($order->id)) }}"; 
 }
 
// These lines will only be reached if the redirection fails or is not immediate
 btn.innerHTML = 'Continue to Payment'; 
 btn.disabled = false; 
 }, 2000); 
 } 
 }); 
 </script> 
</div>
   
        {{-- <div class="bg-gray-800 bg-opacity-70 backdrop-blur-xl rounded-2xl shadow-3xl overflow-hidden transform transition-all duration-500 ease-in-out hover:scale-[1.01] w-full max-w-md border border-gray-700">

           
            <div class="p-6 sm:p-8 border-b border-gray-700 bg-gray-700 bg-opacity-30">
                <h2 class="text-3xl sm:text-4xl font-extrabold text-white text-center tracking-tight mb-2">
                    Select Payment Method
                </h2>
                <p class="text-center text-gray-300 text-sm sm:text-base">
                    Choose how you'd like to complete your transaction securely.
                </p>
            </div>

            
            <div class="p-6 sm:p-8 space-y-6 sm:space-y-8">

                
                <a href="{{ route('paypal.paymentLink', encrypt($order->id)) }}"
                   class="relative group flex items-center justify-center w-full bg-orange-600 hover:bg-orange-700 active:bg-orange-800 text-white font-semibold py-4 px-6 rounded-lg transition-all duration-300 ease-out transform hover:-translate-y-1 shadow-lg hover:shadow-xl focus:outline-none focus:ring-4 focus:ring-orange-500 focus:ring-opacity-50 overflow-hidden">
                    <span class="absolute inset-0 bg-gradient-to-r from-orange-700 to-orange-500 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></span>
                    <div class="relative z-10 flex items-center gap-3">
                        <img src="https://www.paypalobjects.com/webstatic/icon/pp258.png" alt="PayPal" class="h-8 w-auto object-contain brightness-110">
                        <span class="text-lg">Pay with PayPal</span>
                    </div>
                    <span class="absolute right-6 opacity-0 group-hover:opacity-100 transform translate-x-4 group-hover:translate-x-0 transition-all duration-300 ease-out text-white text-xl">
                        <i class="fas fa-chevron-right"></i>
                    </span>
                </a>

               
                <button onclick="window.location.href='{{ route('f.payment.form', encrypt($order->id)) }}'"
                        class="relative group flex items-center justify-center w-full bg-gray-700 hover:bg-gray-600 active:bg-gray-700 text-white font-semibold py-4 px-6 rounded-lg transition-all duration-300 ease-out transform hover:-translate-y-1 shadow-lg hover:shadow-xl focus:outline-none focus:ring-4 focus:ring-orange-500 focus:ring-opacity-50 overflow-hidden border border-gray-600">
                    <span class="absolute inset-0 bg-gradient-to-r from-gray-700 to-gray-600 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></span>
                    <div class="relative z-10 flex items-center gap-3">
                        <span class="text-3xl text-orange-400">
                            <i class="fab fa-cc-stripe"></i>
                        </span>
                        <span class="text-lg">Pay with Stripe</span>
                    </div>
                    <span class="absolute right-6 opacity-0 group-hover:opacity-100 transform translate-x-4 group-hover:translate-x-0 transition-all duration-300 ease-out text-orange-400 text-xl">
                        <i class="fas fa-chevron-right"></i>
                    </span>
                </button>

            </div>

            {{-- Card Footer / Back Link --}}
            {{-- <div class="p-6 sm:p-8 border-t border-gray-700 text-center bg-gray-700 bg-opacity-30">
                <a href="{{ route('user.add-credits') }}"
                   class="inline-flex items-center text-gray-400 hover:text-orange-400 text-sm sm:text-base transition-colors duration-200 group">
                    <svg class="h-4 w-4 mr-2 group-hover:-translate-x-1 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                    <span class="group-hover:underline">Back to Add Credits</span>
                </a>
            </div>
        </div>
    </div>   --}}

 </x-frontend::layout>  