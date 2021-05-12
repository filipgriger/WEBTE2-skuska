$(function () {

    var controls = $('#controls');
    var simpleCount = 0;
    var optionCount = 0;
    var pairCount = 0;
    var optionOptionCount = 0;



    $('#add-simple').click((e) => {
        e.preventDefault();
        addSimpleQuestion();
    });
    $('#add-option').click((e) => {
        e.preventDefault();
        addOptionQuestion();
    });
    $('#add-pair').click((e) => {
        e.preventDefault();
        addPairQuestion();
    });

    function addSimpleQuestion(){
        simpleCount ++;
        let question = '' +
            '<div class="form-group row">' +
            '<input type="hidden" name="questions[' + (simpleCount + optionCount + pairCount) + '][type]" value="simple">' +
            '<div class="col-5">' +
            '<label class="font-weight-bold" for="q-simple' + simpleCount + '">Question ' + (simpleCount + optionCount + pairCount) + '</label>' +
            '<input type="text" id="q-simple' + simpleCount + '" class="form-control" name="questions[' + (simpleCount + optionCount + pairCount) + '][question]">' +
            '</div>' +
            '<div class="col-5">' +
            '<label for="a-simple' + simpleCount + '">Correct answer</label>' +
            '<input id="a-simple' + simpleCount + '" type="text" class="form-control" name="questions[' + (simpleCount + optionCount + pairCount) + '][answer]">' +
            '</div>' +
            '<div class="col-2">' +
            '<label for="points-simple' + simpleCount + '">Max points</label>' +
            '<input id="points-simple' + simpleCount + '" type="number" min="1" class="form-control" name="questions[' + (simpleCount + optionCount + pairCount) + '][points]">' +
            '</div>' +
            '</div>' +
            '<hr class="border">';
        controls.before($(question));
    }

    function addOptionQuestion(){
        optionCount++;
        let question = '' +
            '<div class="form-group row" data-option-count="1" data-index="' + (simpleCount + optionCount + pairCount) + '">' +
            '<input type="hidden" name="questions[' + (simpleCount + optionCount + pairCount) + '][type]" value="option">' +
            '<div class="col-5">' +
            '<label class="font-weight-bold" for="q-option' + optionCount + '">Question ' + (simpleCount + optionCount + pairCount) + '</label>' +
            '<input type="text" id="q-option' + optionCount + '" class="form-control" name="questions[' + (simpleCount + optionCount + pairCount) + '][question]">' +
            '</div>' +
            '<div class="col-5">' +
            '<label for="a-option' + optionCount + '-' + ++optionOptionCount +'">Correct option</label>' +
            '<input type="text" id="a-option' + optionCount + '-' + ++optionOptionCount +'" class="form-control" name="questions[' + (simpleCount + optionCount + pairCount) + '][answer]">' +
            '</div>' +
            '<div class="col-2">' +
            '<label for="points-option' + optionCount + '-' + ++optionOptionCount +'">Max points</label>' +
            '<input type="number" min="1" id="points-option' + optionCount + '-' + ++optionOptionCount +'" class="form-control" name="questions[' + (simpleCount + optionCount + pairCount) + '][points]">' +
            '</div>' +
            '<div class="col-12 my-2"><button class="btn btn-dark btn-block add-option-' + optionCount + '">Add option</button></div>' +
            '</div>' + '<hr class="border">';

        controls.before($(question));

        $('.add-option-'+optionCount).click(function (event) {
            event.preventDefault();
            let question = $(this).parent().parent();
            let questionOptionCount = 1 + parseInt(question.attr('data-option-count'));
            let questionIndex = parseInt(question.attr('data-index'));
            question.attr('data-option-count', questionOptionCount);
            let option = '' +
                '<div class="input-group px-3 my-2">' +
                '<div class="input-group-prepend">' +
                '<div class="input-group-text">Option ' + questionOptionCount + '</div>' +
                '</div>' +
                '<input type="text" class="form-control" name="questions[' + questionIndex + '][options][]">' +
                '</div>';
            $(this).parent().before($(option));
        });
    }

    function addPairQuestion(){
        pairCount++;
        let question = '' +
            '<div class="form-group row" data-pair-count="0" data-index="' + (simpleCount + optionCount + pairCount) + '">' +
            '<input type="hidden" name="questions[' + (simpleCount + optionCount + pairCount) + '][type]" value="pair">' +
            '<div class="col-10">' +
            '<label class="font-weight-bold" for="q-pair' + pairCount + '">Question ' + (simpleCount + optionCount + pairCount) + '</label>' +
            '<input type="text" id="q-pair' + pairCount + '" class="form-control" name="questions[' + (simpleCount + optionCount + pairCount) + '][question]">' +
            '</div>' +
            '<div class="col-2">' +
            '<label for="points-option' + optionCount + '-' + ++optionOptionCount +'">Max points</label>' +
            '<input type="number" min="1" id="points-option' + optionCount + '-' + ++optionOptionCount +'" class="form-control" name="questions[' + (simpleCount + optionCount + pairCount) + '][points]">' +
            '</div>' +
            '<div class="col-12 mt-3 mb-2"><button class="btn btn-dark btn-block add-pair-' + pairCount + '">Add pair</button></div>' +
            '</div>' + '<hr class="border">';

        controls.before($(question));

        $('.add-pair-'+pairCount).click(function (event) {
            event.preventDefault();
            let question = $(this).parent().parent();
            let questionPairCount = 1 + parseInt(question.attr('data-pair-count'));
            let questionIndex = parseInt(question.attr('data-index'));
            question.attr('data-pair-count', questionPairCount);
            let pair = '' +
                '<div class="col-12">' +
                '<div class="row my-2">' +
                '<div class="col-12">Pair ' + questionPairCount + '</div>' +
                    '<div class="col">' +
                    '<input type="text" class="form-control" name="questions[' + questionIndex + '][pairs][' + questionPairCount + '][left]">' +
                    '</div>' +
                    '<div class="col">' +
                    '<input type="text" class="form-control" name="questions[' + questionIndex + '][pairs][' + questionPairCount + '][right]">' +
                    '</div>' +
                '</div>' +
                '</div>' +
                '</div>';
            $(this).parent().before($(pair));
        });

    }
});