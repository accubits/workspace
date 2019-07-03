import { TestBed, inject } from '@angular/core/testing';

import { SettingsApiService } from './settings-api.service';

describe('SettingsApiService', () => {
  beforeEach(() => {
    TestBed.configureTestingModule({
      providers: [SettingsApiService]
    });
  });

  it('should be created', inject([SettingsApiService], (service: SettingsApiService) => {
    expect(service).toBeTruthy();
  }));
});
