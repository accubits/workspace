import { Component, Input, OnInit, Output, EventEmitter , ViewChild , ElementRef } from '@angular/core';
import { DataService } from '../../services/data.service';
import { FormsUtilityService } from '../../services/forms-utility.service';

@Component({
  selector: 'app-address',
  templateUrl: './address.component.html',
  styleUrls: ['./address.component.scss']
})
export class AddressComponent implements OnInit {
  @ViewChild("focus") trgFocusEl: ElementRef;
  @Input() data: any;
  @Output() deleteFromParent = new EventEmitter<string>();
  currentElementIndex: string;
  idx: number;
  currentElement: {};

  /* Data model for address element */
  addressElement = {
    componentId: null,
    action: 'create',
    type: 'address',
    address: {
      label: '',
      isRequired: false
    },
    isValidated:false,
  }

  constructor(public dataService: DataService,
    public formsUtilityService: FormsUtilityService
  ) { }

  ngOnInit() {
    setTimeout(() => {
      this.currentElementIndex = this.data;
      this.currentElement = this.dataService.formElementArray.filter(
        element => element.index === this.currentElementIndex)[0];
      if (Object.getOwnPropertyNames(this.currentElement['element']).length === 0) {
        this.currentElement['element'] = this.addressElement;
      } else {
        this.addressElement = this.currentElement['element'];
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
    setTimeout(()=>{
      this.trgFocusEl.nativeElement.focus();
    },100);
  }
  
  validateElement():void{
    this.addressElement.address.label?this.addressElement.isValidated = true:
    this.addressElement.isValidated = false; 
  }
}
