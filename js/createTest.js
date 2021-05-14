$(function () {

    var controls = $('#controls');
    var form = $('form');
    let simpleCount = 0;
    let optionCount = 0;
    let pairCount = 0;
    let optionOptionCount = 0;
    let imageCount = 0;
    let expressionCount = 0;


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
    $('#add-image').click((e) => {
        e.preventDefault();
        addImageQuestion();
    });
    $('#add-expression').click((e) => {
        e.preventDefault();
        addExpressionQuestion();
    });


    form.on('click', '.delete-question', function(e) {
        e.preventDefault();
        $(this).parent().parent().next().remove();
        $(this).parent().parent().remove();
        $.each($('.question-number'), function (index, item) {
            $(item).text(index+1)
        })
    });

    form.on('click', '.delete-option', function(e) {
        e.preventDefault();
        let option = $(this).parent().parent().parent();
        let question = option.parent();
        option.remove();
        let optionCount = question.attr('data-option-count');
        question.attr('data-option-count', --optionCount);
        $.each(question.find('.option-number'), function (index, item) {
            $(item).text(index+2)
        })
    });

    form.on('click', '.delete-pair', function(e) {
        e.preventDefault();
        let option = $(this).parent().parent().parent();
        let question = option.parent();
        option.remove();
        let pairCount = question.attr('data-pair-count');
        question.attr('data-pair-count', --pairCount);
        $.each(question.find('.pair-number'), function (index, item) {
            $(item).text(index+1)
        })
    });

    function addSimpleQuestion(){
        simpleCount ++;
        let questionCount = $('.question').length;
        let question = '' +
            '<div class="question form-group row">' +
            '<input type="hidden" name="questions[' + (simpleCount + optionCount + pairCount + imageCount + expressionCount) + '][type]" value="simple">' +
            '<div class="col-9">' +
                '<div class="row">' +
                    '<div class="col-6">' +
                    '<label class="font-weight-bold" for="q-simple' + simpleCount + '">Question <span class="question-number">' + (++questionCount) + '</span> [simple]</label>' +
                    '<input type="text" id="q-simple' + simpleCount + '" class="form-control" name="questions[' + (simpleCount + optionCount + pairCount + imageCount + expressionCount) + '][question]">' +
                    '</div>' +

                    '<div class="col-6">' +
                    '<label for="a-simple' + simpleCount + '">Correct answer</label>' +
                    '<input id="a-simple' + simpleCount + '" type="text" class="form-control" name="questions[' + (simpleCount + optionCount + pairCount + imageCount + expressionCount) + '][answer]">' +
                    '</div>' +
                 '</div>' +
            '</div>' +


            '<div class="col-2">' +
            '<label for="points-simple' + simpleCount + '">Max points</label>' +
            '<input id="points-simple' + simpleCount + '" type="number" min="1" class="form-control" name="questions[' + (simpleCount + optionCount + pairCount + imageCount + expressionCount) + '][points]">' +
            '</div>' +

            '<div class="col-1 d-flex justify-content-center"><i class="align-self-end pb-1 fas fa-2x fa-times-circle delete-question btn text-danger"></i></div>' +
            '</div>' +
            '<hr class="border">';

        controls.before($(question));
    }

    function addOptionQuestion(){
        optionCount++;
        let questionCount = $('.question').length;
        let question = '' +
            '<div class="question form-group row" data-option-count="1" data-index="' + (simpleCount + optionCount + pairCount + imageCount + expressionCount) + '">' +
            '<input type="hidden" name="questions[' + (simpleCount + optionCount + pairCount + imageCount) + '][type]" value="option">' +
            '<div class="col-9">' +
                '<div class="row">' +
                    '<div class="col-6">' +
                    '<label class="font-weight-bold" for="q-option' + optionCount + '">Question <span class="question-number">' + (++questionCount) + '</span> [options]</label>' +
                    '<input type="text" id="q-option' + optionCount + '" class="form-control" name="questions[' + (simpleCount + optionCount + pairCount + imageCount + expressionCount) + '][question]">' +
                    '</div>' +
                    '<div class="col-6">' +
                    '<label for="a-option' + optionCount + '-' + ++optionOptionCount +'">Correct option</label>' +
                    '<input type="text" id="a-option' + optionCount + '-' + ++optionOptionCount +'" class="form-control" name="questions[' + (simpleCount + optionCount + pairCount + imageCount + expressionCount) + '][answer]">' +
                    '</div>' +
                '</div>' +
            '</div>' +
            '<div class="col-2">' +
            '<label for="points-option' + optionCount + '-' + ++optionOptionCount +'">Max points</label>' +
            '<input type="number" min="1" id="points-option' + optionCount + '-' + ++optionOptionCount +'" class="form-control" name="questions[' + (simpleCount + optionCount + pairCount + imageCount + expressionCount) + '][points]">' +
            '</div>' +
            '<div class="col-1 d-flex justify-content-center"><i class="align-self-end pb-1 fas fa-2x fa-times-circle delete-question btn text-danger"></i></div>' +
            '<div class="col-12 mt-3 mb-2"><button class="btn btn-dark btn-block add-option-' + optionCount + '">Add option</button></div>' +
            '</div>' + '<hr class="border">';

        controls.before($(question));

        $('.add-option-'+optionCount).click(function (event) {
            event.preventDefault();
            let question = $(this).parent().parent();
            let questionOptionCount = 1 + parseInt(question.attr('data-option-count'));
            let questionIndex = parseInt(question.attr('data-index'));
            question.attr('data-option-count', questionOptionCount);
            let option = '' +
                '<div class="option col-12 mt-3">' +
                '<div class="row">' +
                    '<div class="col-10">' +
                        '<div class="input-group">' +
                            '<div class="input-group-prepend">' +
                            '<div class="input-group-text">Option <span class="pl-1 option-number">' + questionOptionCount + '</span></div>' +
                            '</div>' +
                        '<input type="text" class="form-control" name="questions[' + questionIndex + '][options][]">' +
                        '</div>' +
                    '</div>' +
                    '<div class="col-2 d-flex justify-content-start"><i class="align-self-center pb-1 fas fa-2x fa-times-circle delete-option btn text-danger"></i></div>' +
                '</div>' +
                '</div>' ;
            $(this).parent().before($(option));
        });
    }

    function addPairQuestion(){
        pairCount++;
        let questionCount = $('.question').length;
        let question = '' +
            '<div class="question form-group row" data-pair-count="0" data-index="' + (simpleCount + optionCount + pairCount + imageCount + expressionCount) + '">' +
            '<input type="hidden" name="questions[' + (simpleCount + optionCount + pairCount + imageCount) + '][type]" value="pair">' +
            '<div class="col-9">' +
            '<label class="font-weight-bold" for="q-pair' + pairCount + '">Question <span class="question-number">' + (++questionCount) + '</span> [pairs]</label>' +
            '<input type="text" id="q-pair' + pairCount + '" class="form-control" name="questions[' + (simpleCount + optionCount + pairCount + imageCount + expressionCount) + '][question]">' +
            '</div>' +
            '<div class="col-2">' +
            '<label for="points-option' + optionCount + '-' + ++optionOptionCount +'">Max points</label>' +
            '<input type="number" min="1" id="points-option' + optionCount + '-' + ++optionOptionCount +'" class="form-control" name="questions[' + (simpleCount + optionCount + pairCount + imageCount + expressionCount) + '][points]">' +
            '</div>' +
            '<div class="col-1 d-flex justify-content-center"><i class="align-self-end pb-1 fas fa-2x fa-times-circle delete-question btn text-danger"></i></div>' +
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
                '<div class="col-12">Pair <span class="pair-number">' + questionPairCount + '</span></div>' +
                    '<div class="col-5">' +
                    '<input type="text" class="form-control" name="questions[' + questionIndex + '][pairs][' + questionPairCount + '][left]">' +
                    '</div>' +
                    '<div class="col-5">' +
                    '<input type="text" class="form-control" name="questions[' + questionIndex + '][pairs][' + questionPairCount + '][right]">' +
                    '</div>' +
                    '<div class="col-2 d-flex justify-content-start"><i class="align-self-center fas fa-2x fa-times-circle delete-pair btn text-danger"></i></div>' +
                '</div>' +
                '</div>' +
                '</div>';
            $(this).parent().before($(pair));
        });

    }

    function addImageQuestion(){
        imageCount ++;
        let questionCount = $('.question').length;
        let question = '' +
            '<div class="question form-group row">' +
            '<input type="hidden" name="questions[' + (simpleCount + optionCount + pairCount + imageCount + expressionCount) + '][type]" value="image">' +
            '<div class="col-9">' +
            '<label class="font-weight-bold" for="q-image' + imageCount + '">Question <span class="question-number">' + (++questionCount) + '</span> [drawing]</label>' +
            '<input type="text" id="q-image' + imageCount + '" class="form-control" name="questions[' + (simpleCount + optionCount + pairCount + imageCount + expressionCount) + '][question]">' +
            '</div>' +
            '<div class="col-2">' +
            '<label for="points-image' + imageCount + '">Max points</label>' +
            '<input id="points-image' + imageCount + '" type="number" min="1" class="form-control" name="questions[' + (simpleCount + optionCount + pairCount + imageCount + expressionCount) + '][points]">' +
            '</div>' +
            '<div class="col-1 d-flex justify-content-center"><i class="align-self-end pb-1 fas fa-2x fa-times-circle delete-question btn text-danger"></i></div>' +
            '</div>' +
            '<hr class="border">';
        controls.before($(question));
    }

    function addExpressionQuestion(){
        expressionCount ++;
        let questionCount = $('.question').length;
        let question = '' +
            '<div class="question form-group row">' +
            '<input type="hidden" name="questions[' + (simpleCount + optionCount + pairCount + imageCount + expressionCount) + '][type]" value="expression">' +
            '<div class="col-9">' +
            '<label class="font-weight-bold" for="q-expression' + expressionCount + '">Question <span class="question-number">' + (++questionCount) + '</span> [expression]</label>' +
            '<input type="text" id="q-expression' + expressionCount + '" class="form-control" name="questions[' + (simpleCount + optionCount + pairCount + imageCount + expressionCount) + '][question]">' +
            '</div>' +
            '<div class="col-2">' +
            '<label for="points-expression' + expressionCount + '">Max points</label>' +
            '<input id="points-expression' + expressionCount + '" type="number" min="1" class="form-control" name="questions[' + (simpleCount + optionCount + pairCount + imageCount + expressionCount) + '][points]">' +
            '</div>' +
            '<div class="col-1 d-flex justify-content-center"><i class="align-self-end pb-1 fas fa-2x fa-times-circle delete-question btn text-danger"></i></div>' +
            '</div>' +
            '<hr class="border">';
        controls.before($(question));
    }
});