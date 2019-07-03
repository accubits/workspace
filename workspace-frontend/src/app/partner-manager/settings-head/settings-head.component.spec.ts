import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { SettingsHeadComponent } from './settings-head.component';

describe('SettingsHeadComponent', () => {
  let component: SettingsHeadComponent;
  let fixture: ComponentFixture<SettingsHeadComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ SettingsHeadComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(SettingsHeadComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
