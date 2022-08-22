/*
* Javascript for filter design on courses page
* */

var durationSlider = document.getElementById('filterDurationSlider');
var durationSliderOutput = document.getElementById('filterDurationLabel');
var ratingSlider = document.getElementById('filterRatingSlider');
var ratingSliderOutput = document.getElementById('filterRatingLabel');

var orderSelector = document.getElementById('orderSelector');

window.onload = function () {
    durationSlider.oninput();
    ratingSlider.oninput();
}

durationSlider.oninput = function() {
    var time = this.value;

    if(this.value == this.min) {
        durationSliderOutput.innerHTML = "<div class=\"text-muted\">bármilyen hossz</div>";
        return;
    }

    var hours = Math.floor(time/60);
    var mins = time % 60;

    var hoursString = "";
    var minutesString = "";

    var outputText;

    if(hours) {
        hoursString = hoursString.concat(hours.toString(), " óra")
    }
    if(mins) {
        minutesString = minutesString.concat(" ", mins.toString(), " perc");
    }

    outputText = hoursString.concat(minutesString);

    if(time == this.max) {
        outputText = outputText.concat("+");
    }

    durationSliderOutput.innerHTML = outputText;
}

ratingSlider.oninput = function() {
    var rating = this.value;
    var output;
    if(rating < 5 && rating != 0) {
        output = rating + "+";
    }
    else if(rating == 5) {
        output = rating;
    }
    else {
        output = "<div class=\"text-muted\">bármilyen</div>"
    }
    ratingSliderOutput.innerHTML = output;
}

orderSelector.oninput = function() {
    orderSelector.form.submit();
}
