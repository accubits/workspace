import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { WrRightComponent } from './wr-right.component';

describe('WrRightComponent', () => {
  let component: WrRightComponent;
  let fixture: ComponentFixture<WrRightComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ WrRightComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(WrRightComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
