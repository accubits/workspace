import { Component, OnInit, Input, Output, EventEmitter , ViewChild , ElementRef } from '@angular/core';
import { DataService } from '../../services/data.service';
import { FormsUtilityService } from '../../services/forms-utility.service';

@Component({
  selector: 'app-multiple-choices',
  templateUrl: './multiple-choices.component.html',
  styleUrls: ['./multiple-choices.component.scss']
})
export class MultipleChoicesComponent implements OnInit {
  @ViewChild("focus") trgFocusEl: ElementRef;
  @Input() data: any;
  @Output() deleteFromParent = new EventEmitter<string>();
  currentElementIndex: string;
  showMaxQuantity: false;
  idx: number;
  currentElement: {};

  multipleChoiceElement = {
    componentId: null,
    action: 'create',
    type: 'multipleChoice',
    multipleChoice: {
      label: '',
      choices: [
        {
          optId: null,
          action: 'create',
          label: '',
          maxQuantity: '',
          type: 'multipleChoice'
        },
        {
          optId: null,
          action: 'create',
          label: '',
          maxQuantity: '',
          type: 'multipleChoice'
        }
      ],
      isRequired: false,
      randomize: false,
      allowOther: false,
      otherLabel: ''
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
        this.currentElement['element'] = this.multipleChoiceElement;
      } else {
        this.multipleChoiceElement = this.currentElement['element'];
        if (this.multipleChoiceElement.action === 'update') {
          for (var i = 0; i < this.multipleChoiceElement.multipleChoice.choices.length; i++) {
            this.multipleChoiceElement.multipleChoice.choices[i].action = 'update';
          }
        }
      }
    }, 100);
  }

  public removeItem(item: any, list: any[]): void {
    var nw = JSON.stringify(list);
    console.log(nw);
    list.splice(list.indexOf(item), 1);
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

  // /* Duplicating the selected form element */
  // dulpicateElement() {
  //   this.formsUtilityService.duplicatingSelectedFormElement(this.currentElementIndex)
  // }

 /* Duplicating the selected form element */
 dulpicateElement() {
  this.formsUtilityService.duplicatingMultipleChoiceElement(this.currentElementIndex)
}

  activateElement() {
    this.dataService.formElementToggle.activeIndex = this.currentElementIndex;
    setTimeout(()=>{
      this.trgFocusEl.nativeElement.focus();
    },100);
  }

  /* Adding a new choice */
  addChoice(event): void {
     let choice = {
        optId: null,
        action: 'create',
        label: '',
        maxQuantity: '',
        type: 'multipleChoice'
      }
      this.multipleChoiceElement.multipleChoice.choices.push(choice);
  }

  /* Delete from choice */
  deleteFormChoice(idx): void {
    if (this.multipleChoiceElement.multipleChoice.choices.length === 2) return;
    this.multipleChoiceElement.multipleChoice.choices.splice(idx, 1)
  }

  inserted(e) {

  }
  validateElement(): void {
    if (this.multipleChoiceElement.multipleChoice.label) {
      let invalidChoice = this.multipleChoiceElement.multipleChoice.choices.filter(
        choice => choice.label === '')[0];

      if (invalidChoice) {
        this.multipleChoiceElement.isValidated = false;
      } else {
        this.multipleChoiceElement.isValidated = true;
      }
    } else {
      this.multipleChoiceElement.isValidated = false;
    }
  }
}
