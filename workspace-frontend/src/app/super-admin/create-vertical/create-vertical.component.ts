import { Component, OnInit } from '@angular/core';
import { SuperAdminSandbox } from '../super-admin.sandbox';
import { SuperAdminDataService } from '../../shared/services/super-admin-data.service';

@Component({
  selector: 'app-create-vertical',
  templateUrl: './create-vertical.component.html',
  styleUrls: ['./create-vertical.component.scss']
})
export class CreateVerticalComponent implements OnInit {
  isValidated = true;
  constructor(
    private superadminSandbox: SuperAdminSandbox,
    public superAdminDataService: SuperAdminDataService
  ) { }

  ngOnInit() {
  }

  create(){
    if (!this.validation()) return;
    this.superAdminDataService.verticalDetails.slug = null;
    this.superAdminDataService.verticalDetails.action = 'create';
    this.superAdminDataService.verticalDetails.isActive = true;
    this.superadminSandbox.setVertical();
  }

  update(){
    if (!this.validation()) return;
   this.superadminSandbox.setVertical();
  }

  closePop(){
    this.superAdminDataService.createVertical.show = false;
  }

  validation(): boolean {
    this.superAdminDataService.selectedElement.isValidated = true;
    if (!this.superAdminDataService.verticalDetails.name) this.superAdminDataService.selectedElement.isValidated = false;
    if (!this.superAdminDataService.verticalDetails.description) this.superAdminDataService.selectedElement.isValidated = false;
    return this.superAdminDataService.selectedElement.isValidated;
  }

  
}
