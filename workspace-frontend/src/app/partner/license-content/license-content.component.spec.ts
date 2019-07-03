import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { LicenseContentComponent } from './license-content.component';

describe('LicenseContentComponent', () => {
  let component: LicenseContentComponent;
  let fixture: ComponentFixture<LicenseContentComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ LicenseContentComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(LicenseContentComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
