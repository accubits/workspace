import { element } from 'protractor';
import { Component, Input, OnInit, Output, EventEmitter, ViewChild, ElementRef } from '@angular/core';
import { DataService } from '../../services/data.service';
import { FormsUtilityService } from '../../services/forms-utility.service';

@Component({
  selector: 'app-single-line',
  templateUrl: './single-line.component.html',
  styleUrls: ['./single-line.component.scss']
})
export class SingleLineComponent implements OnInit {
  @ViewChild("focus") trgFocusEl: ElementRef;
  @Input() data: any;
  @Output() deleteFromParent = new EventEmitter<string>();
  currentElementIndex: string;
  idx: number;
  currentElement: {};
  tabindex: number = 1;

  /* Data model for single line text */
  singleLineTextElement = {
    componentId: null,
    action: 'create',
    type: 'singleLineText',
    singleLineText: {
      label: '',
      isRequired: false,
      noDuplicate: false
    },
    isValidated: false,
  }

  constructor(
    public dataService: DataService,
    public formsUtilityService: FormsUtilityService,

  ) { }

  ngOnInit() {
 
    setTimeout(() => {
      // Creating a local instance for number element
      this.currentElementIndex = this.data;
      this.currentElement = this.dataService.formElementArray.filter(
        element => element.index === this.currentElementIndex)[0];
      if (Object.getOwnPropertyNames(this.currentElement['element']).length === 0) {
        this.currentElement['element'] = this.singleLineTextElement;
      } else {
        this.singleLineTextElement = this.currentElement['element'];;
      }
      console.log(this.dataService.formElementArray)
    }, 100)
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
  dulpicateElement() {
    this.formsUtilityService.duplicatingSelectedFormElement(this.currentElementIndex)
  }

  activateElement() {
    this.dataService.formElementToggle.activeIndex = this.currentElementIndex;
    setTimeout(() => {
      this.trgFocusEl.nativeElement.focus();
    }, 100);
  }

  validateElement(): void {
    this.singleLineTextElement.singleLineText.label ? this.singleLineTextElement.isValidated = true :
      this.singleLineTextElement.isValidated = false;
  }

  // onKey($event){
  //   alert("hi");
  // } 

}
