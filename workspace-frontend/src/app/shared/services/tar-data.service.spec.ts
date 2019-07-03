import { TestBed, inject } from '@angular/core/testing';

import { TarDataService } from './tar-data.service';

describe('TarDataService', () => {
  beforeEach(() => {
    TestBed.configureTestingModule({
      providers: [TarDataService]
    });
  });

  it('should be created', inject([TarDataService], (service: TarDataService) => {
    expect(service).toBeTruthy();
  }));
});
