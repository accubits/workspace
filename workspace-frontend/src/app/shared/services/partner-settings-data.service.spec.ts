import { TestBed, inject } from '@angular/core/testing';

import { PartnerSettingsDataService } from './partner-settings-data.service';

describe('PartnerSettingsDataService', () => {
  beforeEach(() => {
    TestBed.configureTestingModule({
      providers: [PartnerSettingsDataService]
    });
  });

  it('should be created', inject([PartnerSettingsDataService], (service: PartnerSettingsDataService) => {
    expect(service).toBeTruthy();
  }));
});
