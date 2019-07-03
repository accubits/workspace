import { Component, OnInit } from '@angular/core';
import {DataService} from '../../../shared/services/data.service'
import {FormsUtilityService} from '../../../shared/services/forms-utility.service'

@Component({
  selector: 'app-delete-confirm-popup',
  templateUrl: './delete-confirm-popup.component.html',
  styleUrls: ['./delete-confirm-popup.component.scss']
})
export class DeleteConfirmPopupComponent implements OnInit {

  constructor(
    public dataService:DataService,
    public formsUtilityService:FormsUtilityService
  ) { }

  ngOnInit() {
  }

  closePopup():void{
    // this.dataService.deletePopup.show =false;
    this.dataService.deletePopup[this.dataService.deleteCurrentElementIndex] =false;
  }

  deleteElement(){
  this.formsUtilityService.deleteSelectedFormElement(this.dataService.deleteCurrentElementIndex);
  this.dataService.deletePopup.show =false;

  }
}
