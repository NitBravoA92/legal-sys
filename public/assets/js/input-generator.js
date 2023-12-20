function* indexMaker(){
    let index = 0;
    while(true)
        yield index++;
}

let index_generator = indexMaker();
const CONTAINER = 'productFieldGroupContainer';

document.getElementById('addNewFieldsGroup').addEventListener('click', function(e){
    productFieldGroup(index_generator.next().value, CONTAINER);
});

function productFieldGroup(index, containerID) {

    let divContainerInput1 = document.createElement('div'); 
    divContainerInput1.classList.add('col-md-4');
    divContainerInput1.classList.add('animate__animated');
    divContainerInput1.classList.add('animate__fadeInUp');

    divContainerInput1.id = 'containerFieldInputEN-'+index;
    document.getElementById(containerID).appendChild(divContainerInput1);
    //label input in english
    let labelInput1 = document.createElement('label');
    labelInput1.setAttribute('for', 'fieldInputEN-'+index);
    labelInput1.innerHTML = "Field Name (EN)";
    divContainerInput1.appendChild(labelInput1);
    labelInput1.classList.add('form-control-label');
    //input
    let input1 = document.createElement('input');
    input1.setAttribute('type', 'text');
    input1.setAttribute('name', 'fieldname_english[]');
    input1.setAttribute('required', 'required');
    input1.id = 'fieldInputEN-'+index;
    divContainerInput1.appendChild(input1);
    input1.classList.add('form-control');



    let divContainerInput2 = document.createElement('div'); 
    divContainerInput2.classList.add('col-md-4');
    divContainerInput2.classList.add('animate__animated');
    divContainerInput2.classList.add('animate__fadeInUp');

    divContainerInput2.id = 'containerFieldInputES-'+index;
    document.getElementById(containerID).appendChild(divContainerInput2);
    //label input in english
    let labelInput2 = document.createElement('label');
    labelInput2.setAttribute('for', 'fieldInputES-'+index);
    labelInput2.innerHTML = "Field Name (ES)";
    divContainerInput2.appendChild(labelInput2);
    labelInput2.classList.add('form-control-label');
    //input
    let input2 = document.createElement('input');
    input2.setAttribute('type', 'text');
    input2.setAttribute('name', 'fieldname_spanish[]');
    input2.setAttribute('required', 'required');
    input2.id = 'fieldInputES-'+index;
    divContainerInput2.appendChild(input2);
    input2.classList.add('form-control');



    let divContainerSelect = document.createElement('div'); 
    divContainerSelect.classList.add('col-md-3');
    divContainerSelect.classList.add('animate__animated');
    divContainerSelect.classList.add('animate__fadeInUp');

    divContainerSelect.id = 'containerFieldSelect-'+index;
    document.getElementById(containerID).appendChild(divContainerSelect);
    //label select
    let labelSelect = document.createElement('label');
    labelSelect.setAttribute('for', 'fieldSelect-'+index);
    labelSelect.innerHTML = "Field Type";
    divContainerSelect.appendChild(labelSelect);
    labelSelect.classList.add('form-control-label');
    
    //select
    let select = document.createElement('select');
    select.setAttribute('name', 'field_type[]');
    select.id = 'fieldSelect-'+index;
    divContainerSelect.appendChild(select);
    select.classList.add('form-control');

        //select options
        let optionText = document.createElement('option');
        optionText.setAttribute('value', 'text');
        optionText.setAttribute('selected', 'selected');
        optionText.innerHTML = 'Text field';

        let optionFile = document.createElement('option');
        optionFile.setAttribute('value', 'file');
        optionFile.innerHTML = 'File Attach field';

        let optionRadio = document.createElement('option');
        optionRadio.setAttribute('value', 'radio');
        optionRadio.innerHTML = 'Yes/No selection field';

        let optionEmail = document.createElement('option');
        optionEmail.setAttribute('value', 'email');
        optionEmail.innerHTML = 'Email field';

        let optionDate = document.createElement('option');
        optionDate.setAttribute('value', 'date');
        optionDate.innerHTML = 'Date field';

        select.appendChild(optionText);
        select.appendChild(optionRadio);
        select.appendChild(optionEmail);
        select.appendChild(optionDate);
        select.appendChild(optionFile);

        
    let divContainerButton = document.createElement('div'); 
    divContainerButton.classList.add('col-md-1');
    divContainerButton.classList.add('d-flex');
    divContainerButton.classList.add('flex-row');
    divContainerButton.classList.add('align-items-end');
    divContainerButton.classList.add('animate__animated');
    divContainerButton.classList.add('animate__fadeInUp');

    divContainerButton.id = 'containerFieldButtonDel-'+index;
    document.getElementById(containerID).appendChild(divContainerButton);

    //button delete
    let buttonDelete = document.createElement('button');
    buttonDelete.setAttribute('type', 'button');
    buttonDelete.setAttribute('onclick', 'deleteFieldsGroup('+index+')');
    buttonDelete.id = '_'+index+'_';
    buttonDelete.innerHTML = '<i class="fas fa-trash-alt text-white"></i>';
    divContainerButton.appendChild(buttonDelete);
    buttonDelete.classList.add('btn');
    buttonDelete.classList.add('bg-gradient-danger');
    buttonDelete.classList.add('mb-1');
    buttonDelete.classList.add('pt-2');
    buttonDelete.classList.add('pb-2');
    buttonDelete.classList.add('ps-3');
    buttonDelete.classList.add('pe-3');
    buttonDelete.classList.add('fs-8');
}

function deleteFieldsGroup(index)
{
    const CONTAINER = document.querySelector('#productFieldGroupContainer');
    const input1 = document.querySelector('#containerFieldInputEN-'+index);
    const input2 = document.querySelector('#containerFieldInputES-'+index);
    const select = document.querySelector('#containerFieldSelect-'+index);
    const button = document.querySelector('#containerFieldButtonDel-'+index);

    input1.classList.remove('animate__fadeInUp');
    input2.classList.remove('animate__fadeInUp');
    select.classList.remove('animate__fadeInUp');
    button.classList.remove('animate__fadeInUp');

    input1.classList.add('animate__fadeOutDown');
    input2.classList.add('animate__fadeOutDown');
    select.classList.add('animate__fadeOutDown');
    button.classList.add('animate__fadeOutDown');

    setTimeout(() => {
        CONTAINER.removeChild(button);
        CONTAINER.removeChild(select);
        CONTAINER.removeChild(input2);
        CONTAINER.removeChild(input1);
    }, 500);
}
