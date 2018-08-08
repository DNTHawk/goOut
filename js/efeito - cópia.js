var inputRange = document.getElementsByClassName('range')[0],
    		maxValue = 100, // the higher the smoother when dragging
    		speed = 5,
    		currValue, rafID;

			// set min/max value
			inputRange.min = 0;
			inputRange.max = maxValue;

			// listen for unlock
			function unlockStartHandler() {
   			 	// clear raf if trying again
                 window.cancelAnimationFrame(rafID);
    			// set to desired value
    			currValue = +this.value;
         }

         function unlockEndHandler() {
           
    			// store current value
    			currValue = +this.value;
                
    // determine if we have reached success or not
    if(currValue >= maxValue) {
    	successHandler();
    }
    else {
    	rafID = window.requestAnimationFrame(animateHandler);
    }
}

// bind events
inputRange.addEventListener('mousedown', unlockStartHandler, false);
inputRange.addEventListener('mousestart', unlockStartHandler, false);
inputRange.addEventListener('mouseup', unlockEndHandler, false);
inputRange.addEventListener('touchend', unlockEndHandler, false);

// move gradient
inputRange.addEventListener('input', function() {
    //Change slide thumb color on way up
    if (this.value > 20) {
    	inputRange.classList.add('ltpurple');
    }
    if (this.value > 40) {
    	inputRange.classList.add('purple');
    }
    if (this.value > 60) {
    	inputRange.classList.add('pink');
    }

    //Change slide thumb color on way down
    if (this.value < 20) {
    	inputRange.classList.remove('ltpurple');
    }
    if (this.value < 40) {
    	inputRange.classList.remove('purple');
    }
    if (this.value < 60) {
    	inputRange.classList.remove('pink');
    }

    
});

