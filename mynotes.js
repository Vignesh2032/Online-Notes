$(function(){
    // define variables
    var activeNote = 0;
    var editMode = false;
    
    // load notes on page load: Ajax call to loadnotes.php
    $.ajax({
        url: "loadnotes.php",
        success: function(data){
            $("#notes").html(data);
            clickonNote();
            clickonDelete();
        },
        error: function(){
            $('#alertContent').text("There was an error with an ajax call. Please try again!");
            $('#alert').fadeIn();
        }
    });

    // add a new note: Ajax call to createnote.php
    $('#addNote').click(function(){
        $.ajax({
            url: 'createnote.php',
            success: function(data){
                if(data == 'error'){
                    $('#alertContent').text("There was an issue inserting the new note in the database!");
                    $('#alert').fadeIn();
                }
                else{
                    //update activeNote to the id of the new note
                    activeNote = data;
                    $("textarea").val("");

                    // show hide elements
                    showHide(["#notePad", "#allNotes"], ["#notes", "#addNote", "#edit", "#done"]);
                    $("textarea").focus();
                }
            },
            error: function(){
                $('#alertContent').text("There was an error with an ajax call. Please try again!");
                $('#alert').fadeIn();
            }
        });
    });

    // type note: Ajax call to updatenote.php
    $("textarea").keyup(function(){
        //ajax call to update the content of the active note
        $.ajax({
            url: "updatenote.php",
            type: "POST",
            //send the current note content with its id to the PHP file
            data: {note: $(this).val(), id: activeNote},
            success: function(data){
                if(data == 'error'){
                    $('#alertContent').text("There was an issue updating the note in the database!");
                    $('#alert').fadeIn();
                }
            },
            error: function(){
                $('#alertContent').text("There was an error with an ajax call. Please try again!");
                $('#alert').fadeIn();
            }
        });
    });

    // click on all notes button to load all notes
    $('#allNotes').click(function(){
        $.ajax({
            url: "loadnotes.php",
            success: function(data){
                $("#notes").html(data);
                showHide(["#notes", "#addNote", "#edit"], ["#allNotes", "#notePad"]);
                clickonNote();
                clickonDelete();
            },
            error: function(){
                $('#alertContent').text("There was an error with an ajax call. Please try again!");
                $('#alert').fadeIn();
            }
        });
    });

    // click on done after editing to switch back to view mode
    $("#done").click(function(){
        //switch to non-edit mode
        editMode = false;
        
        //expand the notes
        $(".noteheader").removeClass("col-xs-7 col-sm-9");
        
        //show hide elements
        showHide(["#edit"], [this, ".delete"]);
    });

    // click on edit to enter edit mode
    $("#edit").click(function(){
        // switch to edit mode
        editMode = true;
        
        //reduce the width of notes
        $(".noteheader").addClass("col-xs-7 col-sm-9");
        
        //show hide elements
        showHide(["#done", ".delete"], ["#edit"]);
    });

    // functions
    // function to handle clicking on a note
    function clickonNote(){
        $(".noteheader").click(function(){
            if(!editMode){
                //update activeNote variable to the id of the note.
                activeNote = $(this).attr("id");
                
                //fill the text area with the content
                $("textarea").val($(this).find('.text').text());
                
                // show hide elements
                showHide(["#notePad", "#allNotes"], ["#notes", "#addNote", "#edit", "#done"]);
                $("textarea").focus();
            }
        });
    }

    // function to handle clicking on delete
    function clickonDelete(){
        $(".delete").click(function(){
            var deleteButton = $(this);
            $.ajax({
                url: "deletenote.php",
                type: "POST",
                // send the id of the note to be deleted
                data: {id: deleteButton.next().attr("id")},
                success: function(data){
                    if(data == "error"){
                        $('#alertContent').text("There was an issue deleting the note from the database!");
                        $('#alert').fadeIn();
                    }
                    else{
                        // remove the note's container div
                        deleteButton.parent().remove();
                    }
                },
                error: function(){
                    $('#alertContent').text("There was an error with an ajax call. Please try again!");
                    $('#alert').fadeIn();
                }
            });
        });
    }

    // function to show and hide elements
    function showHide(array1, array2){
        for(let i = 0; i < array1.length; i++){
            $(array1[i]).show(); // Select the element using jQuery and show it
        }
        for(let i = 0; i < array2.length; i++){
            $(array2[i]).hide(); // Select the element using jQuery and hide it
        }
    } 
});
