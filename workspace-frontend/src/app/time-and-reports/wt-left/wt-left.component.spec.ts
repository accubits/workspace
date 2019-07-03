import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { WtLeftComponent } from './wt-left.component';

describe('WtLeftComponent', () => {
  let component: WtLeftComponent;
  let fixture: ComponentFixture<WtLeftComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ WtLeftComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(WtLeftComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
