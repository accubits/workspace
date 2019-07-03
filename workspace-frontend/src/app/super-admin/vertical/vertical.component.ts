import { Component, OnInit } from '@angular/core';
import { SuperAdminSandbox } from '../super-admin.sandbox';
import { SuperAdminDataService } from '../../shared/services/super-admin-data.service';

@Component({
  selector: 'app-vertical',
  templateUrl: './vertical.component.html',
  styleUrls: ['./vertical.component.scss']
})
export class VerticalComponent implements OnInit {

  constructor(private superadminSandbox: SuperAdminSandbox,
    public superAdminDataService: SuperAdminDataService) { }

  ngOnInit() {
    this.superadminSandbox.getVerticalList();
  }

  /*Update verticals[Start]*/
  update(Vertical, index){
    this.superAdminDataService.verticalDetails.slug = Vertical.slug;
    this.superAdminDataService.verticalDetails.action = 'update';
    this.superAdminDataService.verticalDetails.name = Vertical.name;
    this.superAdminDataService.verticalDetails.description = Vertical.description;
    this.superAdminDataService.createVertical.show = true;
    this.superAdminDataService.optionBtn[index] = false;

  }
  /*Update verticals[End]*/

  showPop(){
    this.superAdminDataService.verticalDetails.slug = null;
    this.superAdminDataService.verticalDetails.action = 'create';
    this.superAdminDataService.verticalDetails.name = '';
    this.superAdminDataService.verticalDetails.description = '';
    this.superAdminDataService.createVertical.show = true;
  }

  /*Delete verticals[Start]*/
  deleteVertical(slug , index) {
    this.superAdminDataService.optionBtn[index] = false;
    this.superAdminDataService.verticalDetails.slug = slug;
    this.superAdminDataService.verticalDetails.action = 'delete';
    this.superAdminDataService.deleteMessage.msg = 'Are you sure you want to delete selected Vertical?'
    this.superAdminDataService.deletePopUp.show = true;
  }
    /*Delete verticals[End]*/

  conformVerticalDelete() {
    this.superadminSandbox.setVertical();
  }
  cancelDelete() {
    this.superAdminDataService.deletePopUp.show = false;
  }

}
