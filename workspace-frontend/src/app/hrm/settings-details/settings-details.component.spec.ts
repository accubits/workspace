import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { SettingsDetailsComponent } from './settings-details.component';

describe('SettingsDetailsComponent', () => {
  let component: SettingsDetailsComponent;
  let fixture: ComponentFixture<SettingsDetailsComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ SettingsDetailsComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(SettingsDetailsComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
