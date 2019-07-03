import { Component, OnInit, Input } from '@angular/core';
import { Configs } from '../../../config';
import { DataService } from '../../../shared/services/data.service';

@Component({
  selector: 'app-section-break-submit',
  templateUrl: './section-break-submit.component.html',
  styleUrls: ['./section-break-submit.component.scss']
})
export class SectionBreakSubmitComponent implements OnInit {
  @Input() data: any;
  public assetUrl = Configs.assetBaseUrl;
  constructor(public dataService: DataService) { }

  /* Data model for checkbox element */
  sectionElement = {
    type: 'section',
    componentId: null,
    section: {
      title: '',
      description: ''
    }
  }

  ngOnInit() {
    setTimeout(() => {
      this.sectionElement = this.data;
    }, 100)
  }

}
