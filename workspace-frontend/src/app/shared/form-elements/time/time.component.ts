import { Component, OnInit, Input, Output, EventEmitter, ViewChild, ElementRef } from '@angular/core';
import { DataService } from '../../services/data.service';
import { FormsUtilityService } from '../../services/forms-utility.service';
import { Configs } from '../../../config';

@Component({
  selector: 'app-time',
  templateUrl: './time.component.html',
  styleUrls: ['./time.component.scss']
})

export class TimeComponent implements OnInit {
  @ViewChild("focus") trgFocusEl: ElementRef;
  @Input() data: any;
  @Output() deleteFromParent = new EventEmitter<string>();
  currentElementIndex: string;
  idx: number;
  currentElement: {};


  /* Data model for time element */
  timeElement = {
    componentId: null,
    action: 'create',
    type: 'time',
    time: {
      label: '',
      isRequired: false,
      noDuplicate: false
    },
    isValidated: false,
  }

  public assetUrl = Configs.assetBaseUrl;
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
        this.currentElement['element'] = this.timeElement;
      } else {
        this.timeElement = this.currentElement['element'];
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
    this.formsUtilityService.duplicatingSelectedFormElement(this.currentElementIndex)
  }

  activateElement() {
    this.dataService.formElementToggle.activeIndex = this.currentElementIndex;
    setTimeout(() => {
      this.trgFocusEl.nativeElement.focus();
    }, 100);
  }
  validateElement(): void {
    this.timeElement.time.label ? this.timeElement.isValidated = true :
      this.timeElement.isValidated = false;
  }
}
