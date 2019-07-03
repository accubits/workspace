import { Component, Input, OnInit, Output, EventEmitter, ViewChild, ElementRef } from '@angular/core';
import { DataService } from '../../services/data.service';
import { FormsUtilityService } from '../../services/forms-utility.service';

@Component({
  selector: 'app-checkboxes',
  templateUrl: './checkboxes.component.html',
  styleUrls: ['./checkboxes.component.scss']
})
export class CheckboxesComponent implements OnInit {
  @ViewChild("focus") trgFocusEl: ElementRef;
  @Input() data: any;
  @Output() deleteFromParent = new EventEmitter<string>();
  currentElementIndex: string;
  showMaxQuantity: false;
  idx: number;
  currentElement: {};

  /* Data model for checkbox element */
  checkboxElement = {
    componentId: null,
    action: 'create',
    type: 'checkboxes',
    checkboxes: {
      label: '',
      choices: [
        {
          optId: null,
          action: 'create',
          label: '',
          maxQuantity: '',
          type: 'checkboxes'
        },
        {
          optId: null,
          action: 'create',
          label: '',
          maxQuantity: '',
          type: 'checkboxes'

        }
      ],
      isRequired: false

    },
    isValidated: false,
  }
  constructor(
    public dataService: DataService,
    public formsUtilityService: FormsUtilityService
  ) { }

  ngOnInit() {
    setTimeout(() => {
      // Creating a local instance of form element array
      this.currentElementIndex = this.data;
      this.currentElement = this.dataService.formElementArray.filter(
        element => element.index === this.currentElementIndex)[0];
      if (Object.getOwnPropertyNames(this.currentElement['element']).length === 0) {
        this.currentElement['element'] = this.checkboxElement;
      } else {
        this.checkboxElement = this.currentElement['element'];
        if (this.checkboxElement.action === 'update') {
          for (var i = 0; i < this.checkboxElement.checkboxes.choices.length; i++) {
            this.checkboxElement.checkboxes.choices[i].action = 'update';
          }
        }
      }
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

dulpicateElement() {
    this.formsUtilityService.duplicatingCheckboxElement(this.currentElementIndex)
  }
  
  
  activateElement() {
    this.dataService.formElementToggle.activeIndex = this.currentElementIndex;
    setTimeout(() => {
      this.trgFocusEl.nativeElement.focus();
    }, 100);
  }

  /* Adding a new choice */
  addChoice(event): void {

    this.checkboxElement.isValidated = true;
    let choice = {
      optId: null,
      action: 'create',
      label: '',
      maxQuantity: '',
      type: 'checkboxes'
    }
    this.checkboxElement.checkboxes.choices.push(choice);
  }

  /* Delete from choice */
  deleteFormChoice(idx): void {
    if (this.checkboxElement.checkboxes.choices.length === 2) return;
    this.checkboxElement.checkboxes.choices.splice(idx, 1)
  }

  public removeItem(item: any, list: any[]): void {
    var nw = JSON.stringify(list);
    console.log(nw);
    list.splice(list.indexOf(item), 1);
  }

  validateElement(): void {
    if (this.checkboxElement.checkboxes.label) {
      let invalidChoice = this.checkboxElement.checkboxes.choices.filter(
        choice => choice.label === '')[0];

      if (invalidChoice) {
        this.checkboxElement.isValidated = false;
      } else {
        this.checkboxElement.isValidated = true;
      }
    } else {
      this.checkboxElement.isValidated = false;
    }
  }
}
