import { Component, OnInit, Input } from '@angular/core';
import { Configs } from '../../../config';
import { DataService } from '../../services/data.service';

@Component({
  selector: 'app-address-preview',
  templateUrl: './address-preview.component.html',
  styleUrls: ['./address-preview.component.scss']
})
export class AddressPreviewComponent implements OnInit {
  @Input() data: any;
  currentElementIndex: string;

  /* Data model for paragraph element */
  addressElement = {
    componentId: null,
    action: 'create',
    type: 'address',
    address: {
      label: '',
      isRequired: true
    }
  }


  public assetUrl = Configs.assetBaseUrl;
  constructor(public dataService: DataService) { }

  currentElement: {}

  ngOnInit() {
    setTimeout(() => {
      this.currentElementIndex = this.data;
      this.currentElement = this.dataService.formElementArray.filter(
        element => element.index === this.currentElementIndex)[0];
      this.addressElement = this.currentElement['element'];
    }, 100);
  }


}
