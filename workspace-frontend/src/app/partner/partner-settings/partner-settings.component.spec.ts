import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { PartnerSettingsComponent } from './partner-settings.component';

describe('PartnerSettingsComponent', () => {
  let component: PartnerSettingsComponent;
  let fixture: ComponentFixture<PartnerSettingsComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ PartnerSettingsComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(PartnerSettingsComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
