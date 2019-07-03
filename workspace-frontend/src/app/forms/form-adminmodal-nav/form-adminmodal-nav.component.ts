import { Component, OnInit } from '@angular/core';

@Component({
  selector: 'app-form-adminmodal-nav',
  templateUrl: './form-adminmodal-nav.component.html',
  styleUrls: ['./form-adminmodal-nav.component.scss']
})
export class FormAdminmodalNavComponent implements OnInit {
  activeView = 'individualResponse';
  showSearch:boolean=false;
  constructor() { }

  ngOnInit() {
  }

}
