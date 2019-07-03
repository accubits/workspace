import { Component, OnInit, Input, Output, EventEmitter, ViewChild, ElementRef } from '@angular/core';
import { DataService } from '../../services/data.service';
import { FormsUtilityService } from '../../services/forms-utility.service';

@Component({
  selector: 'app-number',
  templateUrl: './number.component.html',
  styleUrls: ['./number.component.scss']
})
export class NumberComponent implements OnInit {
  @ViewChild("focus") trgFocusEl: ElementRef;
  @Input() data: any;
  @Output() deleteFromParent = new EventEmitter<string>();
  currentElementIndex: string;
  showRange: false;
  newCurrent: any;
  idx: number;
  isInput:boolean= false;

  /* Data model for number element */
  numberElement = {
    type: 'number',
    componentId: null,
    action: 'create',
    number: {
      isRequired: false,
      noDuplicate: false,
      minRange: '',
      maxRange: '',
      label: ''
    },
    isValidated: false
  }

  currentElement: {}
  constructor(
    public dataService: DataService,
    public formsUtilityService: FormsUtilityService,
  ) { }

  ngOnInit() {
    setTimeout(() => {
      this.currentElementIndex = this.data;
      this.currentElement = this.dataService.formElementArray.filter(
        element => element.index === this.currentElementIndex)[0];

      if (Object.getOwnPropertyNames(this.currentElement['element']).length === 0)  {
        this.currentElement['element'] = this.numberElement;
      } else {
        this.numberElement = this.currentElement['element'];
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
    this.numberElement.number.label ? this.numberElement.isValidated = true :
      this.numberElement.isValidated = false;
  }

  onKey($event){
     if($event.keyCode==9){
       //alert('tab pressed');
       //this.isInput = !this.isInput;
     }
  } 

}
