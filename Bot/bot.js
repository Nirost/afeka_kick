//include('connection.php');
const afeka_book_id = 22;

var fs = require('fs');
var http = require('http');
var request = require('request');

//var imageSearch = require('node-google-image-search');
var Twitter = require('twitter');

var client = new Twitter({
    consumer_key: 'uP3XDPUwcgaMq4uZlKZwvRuMw',
    consumer_secret: '9Ve0jW6yJ73oe8rqu6tbDhrs6t9jKKhGa1fozerW7ch2fRWCuW',
    access_token_key: '3242969406-PeCULUTFi3GYXv2IZeYR26g5TKMsAld0D734guP',
    access_token_secret: 'PDWp867UB775ihqyzoCUvFyDqxQWmtwwQ2Byk1i447jp1'
});

var ToneAnalyzerV3 = require('watson-developer-cloud/tone-analyzer/v3');

var tone_analyzer = new ToneAnalyzerV3({
    username: '13f274b2-a703-4c96-9bfe-184c6a04c5e4',
    password: 'i7FdzgipWyX7',
    version_date: '2016-05-19'
});


var followFilter = {
    follow: '25073877' //realDonaldTrump
};

var stream = client.stream('statuses/filter', followFilter);

stream.on('data', function (event) {

    var tweet = event.text;
    if (!(tweet.includes('RT'))){// || tweet.toLowerCase().includes('realdonaldtrump'))) {

        analyze_text(tweet);

    }

});

stream.on('error', function (error) {
    console.log(error);
});


function analyze_text(text) {
    tone_analyzer.tone({text: text},
        function (err, tone) {
            if (err)
                console.log(err);
            else
                var i;
            var max_score = 0;
            var top_sentiment;
            /*for (i = 0; i < tone.document_tone.tone_categories[0].tones.length; i++) {
                if (tone.document_tone.tone_categories[0].tones[i].score > max_score) {
                    max_score = tone.document_tone.tone_categories[0].tones[i].score;
                    top_sentiment = tone.document_tone.tone_categories[0].tones[i].tone_name
                }
            }*/
            //console.log('tweet is: ' + text + '\nTop sentiment is: ' + top_sentiment);
            //get_image_from_google()
        });
}

/*function get_image_from_google(sentiment, post_text){
    var results = image_search(sentiment,function (results){
    var rand_image = results[Math.floor(Math.random()*(results.length + 1))];
    upload_post(post_text, sentiment,rand_image)}, 0,5);
}*/

function upload_post(post_text, sentiment, pic_name) {
    save_to_db(post_text, sentiment);
    post_to_afeka_face(text, sentiment,pic_name)

    // Closure example for Mica
    var post_with_user_id = post_to_afeka_book(afeka_book_id);
    post_with_user_id(text, top_sentiment);
}


function save_to_db(text, top_sentiment) {
    var data = {
        tweet: text,
        sentiment: top_sentiment
    };

    request.post(
        'http://localhost/php/uploadTweet.php',
        { form: data},
        function (error, response, body) {
            if (!error && response.statusCode == 200) {
                console.log(body)
            }
        }
    );
}

// Not a closure

function post_to_afeka_face(text, top_sentiment, pic_name) {

    var postTxt = "Donald just tweeted: " + text + ". Seems like the tweet's main sentiment was: " + top_sentiment;

    var data = {
        user_id: 22,
        post_text: postTxt,
        isPrivate: 0,
        picName: pic_name
    };

    request.post(
        'http://localhost/php/uploadPost.php',
        { form: data},
        function (error, response, body) {
            if (!error && response.statusCode == 200) {
                console.log(body)
            }
        }
    );

}

// Closure:
/*

A closure is the combination of a function and the lexical environment within which that function was declared.
This environment consists of any local variables that were in-scope at the time that the closure was created.
In this case, myFunc is a reference to the instance of the function displayName created when makeFunc is run.
The instance of displayName maintains a reference to its lexical environment, within which the variable name exists.
For this reason, when myFunc is invoked, the variable name remains available for use and "Mozilla" is passed to alert.

-> user_id, isPrivate and url variables remain accessible.

 */

function post_to_afeka_book(user_id) {
    var isPrivate = 0;
    var url = 'http://localhost/php/uploadPost.php';

    return function(text, top_sentiment){
        var postTxt = "Donald just tweeted: " + text + ". Seems like the tweet's main sentiment was: " + top_sentiment;
        var data = {
            user_id: user_id,
            post_text: postTxt,
            isPrivate: isPrivate
        };

        request.post(
            url,
            { form: data},
            function (error, response, body) {
                if (!error && response.statusCode == 200) {
                    console.log(body)
                }
            }
        );

    }
}