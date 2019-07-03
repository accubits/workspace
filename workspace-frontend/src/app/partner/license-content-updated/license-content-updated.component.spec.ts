import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { LicenseContentUpdatedComponent } from './license-content-updated.component';

describe('LicenseContentUpdatedComponent', () => {
  let component: LicenseContentUpdatedComponent;
  let fixture: ComponentFixture<LicenseContentUpdatedComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ LicenseContentUpdatedComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(LicenseContentUpdatedComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
