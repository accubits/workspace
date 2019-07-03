import { Component, OnInit, Output, EventEmitter, Input, ViewChild, ElementRef } from '@angular/core';
import { DataService } from '../../services/data.service';
import { FormsUtilityService } from '../../services/forms-utility.service';

@Component({
  selector: 'app-dropdown',
  templateUrl: './dropdown.component.html',
  styleUrls: ['./dropdown.component.scss']
})
export class DropdownComponent implements OnInit {
  @ViewChild("focus") trgFocusEl: ElementRef;
  @Input() data: any;
  @Output() deleteFromParent = new EventEmitter<string>();
  newElement: any;
  currentElementIndex: string;
  showMaxQuantity: false;
  idx: number;
  currentElement: {};

  /* Data Model for dropdown element */
  dropdownElement = {
    componentId: null,
    action: 'create',
    type: 'dropdown',
    dropdown: {
      label: '',
      choices: [
        {
          optId: null,
          action: 'create',
          label: '',
          maxQuantity: ''
        },
        {
          optId: null,
          action: 'create',
          label: '',
          maxQuantity: ''
        }
      ],
      isRequired: false,
      randomize: false,
      allowOther: false,
      otherLabel: ''
    },
    isValidated: false,
  }

  constructor(public dataService: DataService,
    public formsUtilityService: FormsUtilityService
  ) { }

  ngOnInit() {
    setTimeout(() => {
      // Creating a local instance of form element array
      this.currentElementIndex = this.data;
      this.currentElement = this.dataService.formElementArray.filter(
        element => element.index === this.currentElementIndex)[0];
      if (Object.getOwnPropertyNames(this.currentElement['element']).length === 0) {
        this.currentElement['element'] = this.dropdownElement;
      } else {
        this.dropdownElement = this.currentElement['element'];
        if (this.dropdownElement.action === 'update') {
          for (var i = 0; i < this.dropdownElement.dropdown.choices.length; i++) {
            this.dropdownElement.dropdown.choices[i].action = 'update';
          }
        }
      }
      console.log(this.dataService.formElementToggle.activeIndex);
      console.log(this.currentElementIndex);
    }, 100);
  }

  /* Deleting the selected form element */
  deleteElement() {
    this.idx = 1;
    this.dataService.deletePopup[this.idx] = true;
    this.dataService.deleteCurrentElementIndex = this.currentElementIndex;
  }
  closePopup(): void {
    this.dataService.deletePopup[this.idx] = false;
  }
  deleteConform() {
    this.formsUtilityService.deleteSelectedFormElement(this.dataService.deleteCurrentElementIndex);
    this.dataService.deletePopup[this.idx] = false;
  }
  /* Deleting the selected form element */

  /* Duplicating the selected form element */
  // dulpicateElement() {
  //   this.formsUtilityService.duplicatingSelectedFormElement(this.currentElementIndex)
  // }

/* Duplicating the selected form element */
dulpicateElement() {
  this.formsUtilityService.duplicatingDropDownElement(this.currentElementIndex)
}


  
  activateElement() {
    this.dataService.formElementToggle.activeIndex = this.currentElementIndex;
    setTimeout(() => {
      this.trgFocusEl.nativeElement.focus();
    }, 100);
  }

  /* Adding a new choice */
  addChoice(event): void {
    let choice = {
      optId: null,
      action: 'create',
      label: '',
      maxQuantity: ''
    }
    this.dropdownElement.dropdown.choices.push(choice);
  }

  /* Delete from choice */
  deleteFormChoice(idx): void {
    if (this.dropdownElement.dropdown.choices.length === 2) return;
    this.dropdownElement.dropdown.choices.splice(idx, 1)
  }
  validateElement(): void {
    if (this.dropdownElement.dropdown.label) {
      let invalidChoice = this.dropdownElement.dropdown.choices.filter(
        choice => choice.label === '')[0];

      if (invalidChoice) {
        this.dropdownElement.isValidated = false;
      } else {
        this.dropdownElement.isValidated = true;
      }
    } else {
      this.dropdownElement.isValidated = false;
    }
  }
}
