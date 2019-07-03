import { Component, Input, OnInit, Output, EventEmitter , ViewChild , ElementRef } from '@angular/core';
import { DataService } from '../../services/data.service';
import { FormsUtilityService } from '../../services/forms-utility.service';

@Component({
  selector: 'app-date',
  templateUrl: './date.component.html',
  styleUrls: ['./date.component.scss']
})
export class DateComponent implements OnInit {
  @ViewChild("focus") trgFocusEl: ElementRef;
  @Input() data: any;
  @Output() deleteFromParent = new EventEmitter<string>();
  currentElementIndex: string;
  currentElement: {};
  idx: number;

  /* Data model for date element */
  dateElement = {
    componentId: null,
    action: 'create',
    type: 'date',
    date: {
      label: '',
      isRequired: false,
      noDuplicate: false
    },
    isValidated:false,
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
        this.currentElement['element'] = this.dateElement;
      } else {
        this.dateElement = this.currentElement['element'];
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
  dulpicateElement() {
    this.formsUtilityService.duplicatingSelectedFormElement(this.currentElementIndex);
  }

  activateElement() {
    this.dataService.formElementToggle.activeIndex = this.currentElementIndex;
    setTimeout(()=>{
      this.trgFocusEl.nativeElement.focus();
    },100);
  }
  
  validateElement():void{
    this.dateElement.date.label?this.dateElement.isValidated = true:
    this.dateElement.isValidated = false; 
  }
}
